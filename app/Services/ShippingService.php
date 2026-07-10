<?php

namespace App\Services;

use App\Models\CartSession;
use App\Models\Order;
use App\Models\OrderShipment;
use App\Models\Setting;
use App\Models\ShippingPackage;
use App\Models\ShippingQuote;
use App\Models\ShippingRate;
use App\Models\ShippingTrackingEvent;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class ShippingService
{
    public function calculatePhysicalWeight($cartOrOrder): float
    {
        $items = $cartOrOrder instanceof Order ? $cartOrOrder->items()->with('product', 'variant', 'pack.items.product', 'pack.items.variant')->get() : $cartOrOrder->items()->with('product', 'variant', 'pack.items.product', 'pack.items.variant')->get();

        return (float) $items->sum(function ($item) {
            if ($item->item_type === 'pack') {
                $packItems = $item->pack?->items ?? collect();
                return $packItems->sum(fn ($component) => (float) ($component->variant?->weight ?? $component->product?->weight ?? 0) * $component->quantity * $item->quantity);
            }

            return (float) ($item->variant?->weight ?? $item->product?->weight ?? 0) * $item->quantity;
        });
    }

    public function calculateVolumetricWeight($cartOrOrder): float
    {
        $dimensions = $this->getPackageDimensions($cartOrOrder);
        if (! $dimensions['height'] || ! $dimensions['width'] || ! $dimensions['length']) {
            return 0;
        }

        $factor = max(1, (float) Setting::current()->shipping_volumetric_factor);
        return round(($dimensions['height'] * $dimensions['width'] * $dimensions['length']) / $factor, 3);
    }

    public function calculateBillableWeight($cartOrOrder): float
    {
        return max($this->calculatePhysicalWeight($cartOrOrder), $this->calculateVolumetricWeight($cartOrOrder));
    }

    public function getPackageDimensions($cartOrOrder): array
    {
        $items = $cartOrOrder instanceof Order ? $cartOrOrder->items()->with('product', 'variant', 'pack.items.product', 'pack.items.variant')->get() : $cartOrOrder->items()->with('product', 'variant', 'pack.items.product', 'pack.items.variant')->get();
        $height = 0; $width = 0; $length = 0;

        foreach ($items as $item) {
            $components = $item->item_type === 'pack' ? ($item->pack?->items ?? collect()) : collect([$item]);
            foreach ($components as $component) {
                $variant = $item->item_type === 'pack' ? $component->variant : $item->variant;
                $product = $item->item_type === 'pack' ? $component->product : $item->product;
                $height = max($height, (float) ($variant?->height ?? $product?->height ?? 0));
                $width = max($width, (float) ($variant?->width ?? $product?->width ?? 0));
                $length += (float) ($variant?->length ?? $product?->length ?? 0) * ($item->item_type === 'pack' ? $component->quantity * $item->quantity : $item->quantity);
            }
        }

        return compact('height', 'width', 'length');
    }

    public function findAvailableRates(array $destination, float $billableWeight, array $dimensions = []): Collection
    {
        $this->validateShippingAddress($destination);
        $volume = ($dimensions['height'] ?? 0) * ($dimensions['width'] ?? 0) * ($dimensions['length'] ?? 0);

        return ShippingRate::query()
            ->with(['carrier', 'service', 'zone'])
            ->where('is_active', true)
            ->where('min_weight', '<=', $billableWeight)
            ->where(fn ($q) => $q->whereNull('max_weight')->orWhere('max_weight', '>=', $billableWeight))
            ->get()
            ->filter(fn ($rate) => $rate->isCurrentlyActive()
                && $rate->carrier?->is_active
                && (! $rate->service || $rate->service->is_active)
                && $this->zoneMatches($rate->zone, $destination)
                && (! $rate->max_height || ($dimensions['height'] ?? 0) <= $rate->max_height)
                && (! $rate->max_width || ($dimensions['width'] ?? 0) <= $rate->max_width)
                && (! $rate->max_length || ($dimensions['length'] ?? 0) <= $rate->max_length)
                && (! $rate->max_volume || $volume <= $rate->max_volume)
            )
            ->sortBy(fn ($rate) => [$this->zoneSpecificity($rate->zone), (float) $rate->price])
            ->values();
    }

    public function chooseBestRate(Collection $rates): ?ShippingRate
    {
        return $rates->sortByDesc(fn ($rate) => $this->zoneSpecificity($rate->zone))->sortBy('price')->first();
    }

    public function quoteCartShipping(CartSession $cart, array $destination): Collection
    {
        $dimensions = $this->getPackageDimensions($cart);
        $physical = $this->calculatePhysicalWeight($cart);
        $volumetric = $this->calculateVolumetricWeight($cart);
        $billable = max($physical, $volumetric);
        ShippingPackage::query()->updateOrCreate(['cart_session_id' => $cart->id], ['weight' => $physical, ...$dimensions, 'volumetric_weight' => $volumetric, 'billable_weight' => $billable]);
        $rates = $this->findAvailableRates($destination, $billable, $dimensions);

        return $rates->map(fn ($rate) => ShippingQuote::query()->create([
            'cart_session_id' => $cart->id,
            'shipping_carrier_id' => $rate->shipping_carrier_id,
            'shipping_service_id' => $rate->shipping_service_id,
            'shipping_rate_id' => $rate->id,
            'destination_country' => $destination['country'],
            'destination_region' => $destination['region'],
            'destination_commune' => $destination['commune'],
            'destination_city' => $destination['city'] ?? null,
            'physical_weight' => $physical,
            'volumetric_weight' => $volumetric,
            'billable_weight' => $billable,
            'price' => $rate->price,
            'currency' => $rate->currency,
            'estimated_days_min' => $rate->service?->estimated_days_min,
            'estimated_days_max' => $rate->service?->estimated_days_max,
            'expires_at' => now()->addMinutes(30),
            'metadata' => ['dimensions' => $dimensions],
        ]));
    }

    public function quoteOrderShipping(Order $order): ?ShippingQuote
    {
        $address = $order->addresses()->where('address_type', 'shipping')->first();
        if (! $address) return null;
        $quotes = $this->quoteCartShipping($order->cart, $address->only(['country', 'region', 'commune', 'city']));
        return $quotes->first();
    }

    public function generateTrackingUrl(OrderShipment $shipment): ?string
    {
        $template = $shipment->carrier?->tracking_url_template;
        if (! $template || ! $shipment->tracking_number) return null;
        return str_replace('{tracking_number}', $shipment->tracking_number, $template);
    }

    public function validateShippingAddress(array $address): void
    {
        foreach (['country', 'region', 'commune'] as $field) {
            if (empty($address[$field])) {
                throw ValidationException::withMessages([$field => 'Destino incompleto para cotizar envío.']);
            }
        }
    }

    public function registerTracking(OrderShipment $shipment, string $status, ?string $description = null, ?string $location = null): ShippingTrackingEvent
    {
        $event = $shipment->trackingEvents()->create(['status' => $status, 'description' => $description, 'location' => $location, 'event_at' => now()]);
        AuditLogger::record('created', 'shipping_tracking', "Tracking {$status} para envío #{$shipment->id}");
        return $event;
    }

    private function zoneMatches($zone, array $destination): bool
    {
        if (! $zone || ! $zone->is_active) return false;
        if (strcasecmp($zone->country, $destination['country']) !== 0) return false;
        if ($zone->region && strcasecmp($zone->region, $destination['region'] ?? '') !== 0) return false;
        if ($zone->commune && strcasecmp($zone->commune, $destination['commune'] ?? '') !== 0) return false;
        if ($zone->city && strcasecmp($zone->city, $destination['city'] ?? '') !== 0) return false;
        return true;
    }

    private function zoneSpecificity($zone): int
    {
        return ($zone?->commune ? 4 : 0) + ($zone?->city ? 3 : 0) + ($zone?->region ? 2 : 0) + 1;
    }
}
