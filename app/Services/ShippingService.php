<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\CartSession;
use App\Models\Order;
use App\Models\OrderShipment;
use App\Models\Setting;
use App\Models\ShippingPackage;
use App\Models\ShippingQuote;
use App\Models\ShippingRate;
use App\Models\ShippingTrackingEvent;
use App\Models\StockReservation;
use App\Models\Warehouse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ShippingService
{
    public function __construct(
        private CartService $cartService,
        private DeliveryRegionService $deliveryRegions,
    ) {}

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
        $height = 0;
        $width = 0;
        $length = 0;

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
        return $rates->sort(function (ShippingRate $left, ShippingRate $right): int {
            $specificity = $this->zoneSpecificity($right->zone) <=> $this->zoneSpecificity($left->zone);

            return $specificity !== 0 ? $specificity : (float) $left->price <=> (float) $right->price;
        })->first();
    }

    public function quoteCartShipping(CartSession $cart, array $destination): Collection
    {
        $this->validateShippingAddress($destination);

        return DB::transaction(function () use ($cart, $destination): Collection {
            $packages = $this->warehousePackages($cart, $destination);
            $estimate = $this->cartService->deliveryEstimate($cart);
            $physical = (float) $packages->sum('physical_weight');
            $volumetric = (float) $packages->sum('volumetric_weight');
            $billable = (float) $packages->sum('billable_weight');
            $packageCount = $packages->count();

            ShippingPackage::query()->updateOrCreate(['cart_session_id' => $cart->id], [
                'weight' => $physical,
                'height' => $packages->max('dimensions.height'),
                'width' => $packages->max('dimensions.width'),
                'length' => $packages->sum('dimensions.length'),
                'volumetric_weight' => $volumetric,
                'billable_weight' => $billable,
                'package_count' => $packageCount,
                'notes' => json_encode($packages->map(fn (array $package) => [
                    'warehouse_id' => $package['warehouse']->id,
                    'warehouse_code' => $package['warehouse']->code,
                    'billable_weight' => $package['billable_weight'],
                ])->values()->all(), JSON_UNESCAPED_UNICODE),
            ]);

            ShippingQuote::query()->where('cart_session_id', $cart->id)->delete();

            return $this->aggregatePackageRates($packages)
                ->map(function (array $offer) use ($cart, $destination, $estimate, $physical, $volumetric, $billable, $packageCount): ShippingQuote {
                    /** @var ShippingRate $rate */
                    $rate = $offer['rates']->first();
                    $pickup = $rate->service?->service_type === 'pickup';

                    return ShippingQuote::query()->create([
                        'cart_session_id' => $cart->id,
                        'shipping_carrier_id' => $rate->shipping_carrier_id,
                        'shipping_service_id' => $rate->shipping_service_id,
                        'shipping_rate_id' => $rate->id,
                        'destination_country' => $destination['country'],
                        'destination_region' => $this->deliveryRegions->label($destination['region']) ?? $destination['region'],
                        'destination_commune' => $destination['commune'],
                        'destination_city' => $destination['city'] ?? null,
                        'physical_weight' => $physical,
                        'volumetric_weight' => $volumetric,
                        'billable_weight' => $billable,
                        'price' => $offer['price'],
                        'currency' => $rate->currency,
                        'estimated_days_min' => $pickup ? 0 : $estimate['min'],
                        'estimated_days_max' => $pickup ? 1 : $estimate['max'],
                        'expires_at' => now()->addMinutes(30),
                        'metadata' => [
                            'multi_origin' => $packageCount > 1,
                            'package_count' => $packageCount,
                            'origins' => $offer['packages'],
                        ],
                    ]);
                })
                ->sortBy('price')
                ->values();
        });
    }

    public function quoteOrderShipping(Order $order): ?ShippingQuote
    {
        $address = $order->addresses()->where('address_type', 'shipping')->first();
        if (! $address) {
            return null;
        }
        $quotes = $this->quoteCartShipping($order->cart, $address->only(['country', 'region', 'commune', 'city']));

        return $quotes->first();
    }

    public function generateTrackingUrl(OrderShipment $shipment): ?string
    {
        $template = $shipment->carrier?->tracking_url_template;
        if (! $template || ! $shipment->tracking_number) {
            return null;
        }

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
        if (! $zone || ! $zone->is_active) {
            return false;
        }
        if (strcasecmp($zone->country, $destination['country']) !== 0) {
            return false;
        }
        if ($zone->region && $this->deliveryRegions->normalize($zone->region) !== $this->deliveryRegions->normalize($destination['region'] ?? null)) {
            return false;
        }
        if ($zone->commune && strcasecmp($zone->commune, $destination['commune'] ?? '') !== 0) {
            return false;
        }
        if ($zone->city && strcasecmp($zone->city, $destination['city'] ?? '') !== 0) {
            return false;
        }

        return true;
    }

    private function zoneSpecificity($zone): int
    {
        return ($zone?->commune ? 4 : 0) + ($zone?->city ? 3 : 0) + ($zone?->region ? 2 : 0) + 1;
    }

    private function warehousePackages(CartSession $cart, array $destination): Collection
    {
        $items = $cart->items()->with(['product', 'variant', 'pack.items.product', 'pack.items.variant'])->get();
        $reservations = StockReservation::query()
            ->with('warehouse')
            ->where('reference_type', CartItem::class)
            ->whereIn('reference_id', $items->pluck('id'))
            ->where('status', 'active')
            ->get()
            ->groupBy('reference_id');

        $grouped = $items->groupBy(function (CartItem $item) use ($reservations): int {
            $itemReservations = $reservations->get($item->id, collect());
            $warehouseIds = $itemReservations->pluck('warehouse_id')->unique();

            if ($warehouseIds->count() !== 1) {
                throw ValidationException::withMessages(['shipping' => 'No fue posible determinar una única bodega de origen para cada producto.']);
            }

            return (int) $warehouseIds->first();
        });

        return $grouped->map(function (Collection $warehouseItems, int $warehouseId) use ($destination): array {
            $warehouse = Warehouse::query()->whereKey($warehouseId)->where('is_active', true)->firstOrFail();
            $metrics = $this->packageMetrics($warehouseItems);

            return $metrics + [
                'warehouse' => $warehouse,
                'rates' => $this->findAvailableRates($destination, $metrics['billable_weight'], $metrics['dimensions']),
            ];
        })->values();
    }

    private function packageMetrics(Collection $items): array
    {
        $physical = 0.0;
        $height = 0.0;
        $width = 0.0;
        $length = 0.0;

        foreach ($items as $item) {
            $components = $item->item_type === 'pack' ? ($item->pack?->items ?? collect()) : collect([$item]);
            foreach ($components as $component) {
                $variant = $item->item_type === 'pack' ? $component->variant : $item->variant;
                $product = $item->item_type === 'pack' ? $component->product : $item->product;
                $quantity = $item->item_type === 'pack' ? $component->quantity * $item->quantity : $item->quantity;
                $physical += (float) ($variant?->weight ?? $product?->weight ?? 0) * $quantity;
                $height = max($height, (float) ($variant?->height ?? $product?->height ?? 0));
                $width = max($width, (float) ($variant?->width ?? $product?->width ?? 0));
                $length += (float) ($variant?->length ?? $product?->length ?? 0) * $quantity;
            }
        }

        $dimensions = compact('height', 'width', 'length');
        $factor = max(1, (float) Setting::current()->shipping_volumetric_factor);
        $volumetric = ($height && $width && $length) ? round(($height * $width * $length) / $factor, 3) : 0.0;

        return [
            'physical_weight' => round($physical, 3),
            'volumetric_weight' => $volumetric,
            'billable_weight' => max($physical, $volumetric),
            'dimensions' => $dimensions,
        ];
    }

    private function aggregatePackageRates(Collection $packages): Collection
    {
        $packages = $packages->map(function (array $package): array {
            $package['service_rates'] = $package['rates']
                ->groupBy(fn (ShippingRate $rate) => $rate->shipping_carrier_id.':'.($rate->shipping_service_id ?? 0))
                ->map(fn (Collection $rates) => $this->chooseBestRate($rates));

            return $package;
        });

        $commonServices = $packages
            ->map(fn (array $package) => $package['service_rates']->keys())
            ->reduce(fn (?Collection $common, Collection $keys) => $common === null ? $keys : $common->intersect($keys)->values());

        return collect($commonServices)->map(function (string $serviceKey) use ($packages): ?array {
            $rates = $packages->map(fn (array $package) => $package['service_rates']->get($serviceKey));
            $first = $rates->first();

            if (! $first || ($packages->count() > 1 && $first->service?->service_type === 'pickup')) {
                return null;
            }

            return [
                'rates' => $rates,
                'price' => (float) $rates->sum(fn (ShippingRate $rate) => (float) $rate->price),
                'packages' => $packages->values()->map(function (array $package, int $index) use ($rates): array {
                    /** @var ShippingRate $rate */
                    $rate = $rates->get($index);

                    return [
                        'warehouse_id' => $package['warehouse']->id,
                        'warehouse_code' => $package['warehouse']->code,
                        'rate_id' => $rate->id,
                        'price' => (float) $rate->price,
                        'physical_weight' => $package['physical_weight'],
                        'volumetric_weight' => $package['volumetric_weight'],
                        'billable_weight' => $package['billable_weight'],
                        'dimensions' => $package['dimensions'],
                    ];
                })->all(),
            ];
        })->filter()->values();
    }
}
