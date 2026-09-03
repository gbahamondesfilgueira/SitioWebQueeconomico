<?php

namespace App\Services;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DeliveryRegionService
{
    public const CENTRAL_REGION = 'metropolitana';

    public function regions(): array
    {
        return [
            'arica-y-parinacota' => 'Región de Arica y Parinacota',
            'tarapaca' => 'Región de Tarapacá',
            'antofagasta' => 'Región de Antofagasta',
            'atacama' => 'Región de Atacama',
            'coquimbo' => 'Región de Coquimbo',
            'valparaiso' => 'Región de Valparaíso',
            self::CENTRAL_REGION => 'Región Metropolitana',
            'ohiggins' => "Región de O'Higgins",
            'maule' => 'Región del Maule',
            'nuble' => 'Región de Ñuble',
            'biobio' => 'Región del Biobío',
            'araucania' => 'Región de La Araucanía',
            'los-rios' => 'Región de Los Ríos',
            'los-lagos' => 'Región de Los Lagos',
            'aysen' => 'Región de Aysén',
            'magallanes' => 'Región de Magallanes',
        ];
    }

    public function normalize(?string $region): ?string
    {
        if (blank($region)) {
            return null;
        }

        $slug = Str::slug($region);
        $aliases = [
            'rm' => self::CENTRAL_REGION,
            'region-metropolitana' => self::CENTRAL_REGION,
            'region-metropolitana-de-santiago' => self::CENTRAL_REGION,
            'metropolitana-de-santiago' => self::CENTRAL_REGION,
            'santiago' => self::CENTRAL_REGION,
            'region-del-biobio' => 'biobio',
            'region-del-bio-bio' => 'biobio',
            'bio-bio' => 'biobio',
            'region-de-nuble' => 'nuble',
            'region-de-la-araucania' => 'araucania',
            'la-araucania' => 'araucania',
            'region-de-los-rios' => 'los-rios',
            'region-de-los-lagos' => 'los-lagos',
            'region-del-libertador-general-bernardo-ohiggins' => 'ohiggins',
            'region-de-ohiggins' => 'ohiggins',
            'libertador-general-bernardo-ohiggins' => 'ohiggins',
            'region-de-aysen-del-general-carlos-ibanez-del-campo' => 'aysen',
            'region-de-aysen' => 'aysen',
            'region-de-magallanes-y-de-la-antartica-chilena' => 'magallanes',
        ];

        if (isset($aliases[$slug])) {
            return $aliases[$slug];
        }

        $withoutPrefix = preg_replace('/^region-(de-la|de-los|de|del)-/', '', $slug);
        $withoutPrefix = $withoutPrefix === 'bio-bio' ? 'biobio' : $withoutPrefix;

        return array_key_exists($withoutPrefix, $this->regions()) ? $withoutPrefix : $slug;
    }

    public function label(?string $region): ?string
    {
        $code = $this->normalize($region);

        return $code ? ($this->regions()[$code] ?? Str::headline($code)) : null;
    }

    public function currentRegion(?User $user = null): ?string
    {
        $user ??= auth()->user();
        $address = $user?->customerProfile?->addresses()
            ->where('is_active', true)
            ->where('address_type', 'shipping')
            ->orderByDesc('is_default')
            ->latest()
            ->first();

        return $this->normalize($address?->region);
    }

    public function currentLocalStockRegion(): ?string
    {
        $region = $this->normalize(session('store.local_stock_region'));
        $detectedAt = (int) session('store.local_stock_detected_at', 0);

        if (! $region || ! array_key_exists($region, $this->regions()) || $detectedAt < now()->subMinutes(30)->timestamp) {
            return null;
        }

        return $region;
    }

    public function rememberLocalStockRegion(string $region): void
    {
        $normalized = $this->normalize($region);

        if (! $normalized || ! array_key_exists($normalized, $this->regions())) {
            return;
        }

        session([
            'store.local_stock_region' => $normalized,
            'store.local_stock_detected_at' => now()->timestamp,
        ]);
    }

    public function warehouseCandidates(?string $destinationRegion): Collection
    {
        $destination = $this->normalize($destinationRegion);
        $warehouses = Warehouse::query()
            ->where('is_active', true)
            ->orderBy('fulfillment_priority')
            ->orderBy('id')
            ->get();

        $regional = $destination && $destination !== self::CENTRAL_REGION
            ? $warehouses->filter(fn (Warehouse $warehouse) => ! $warehouse->is_central
                && $warehouse->type === 'branch'
                && $this->normalize($warehouse->region) === $destination)
            : collect();

        $central = $warehouses->filter(fn (Warehouse $warehouse) => $warehouse->is_central)
            ->whenEmpty(fn () => $warehouses->filter(fn (Warehouse $warehouse) => $warehouse->code === 'ECOM'));

        return $regional->concat($central)->unique('id')->values();
    }

    public function deliveryEstimate(?string $destinationRegion, Collection $sourceWarehouses): array
    {
        $destination = $this->normalize($destinationRegion);
        if (! $destination || $sourceWarehouses->isEmpty()) {
            return ['min' => 1, 'max' => 5, 'label' => '1 a 5 días hábiles', 'uses_fallback' => true];
        }

        $usesFallback = $sourceWarehouses->contains(
            fn (Warehouse $warehouse) => $this->normalize($warehouse->region) !== $destination
        );

        return $usesFallback
            ? ['min' => 1, 'max' => 5, 'label' => '1 a 5 días hábiles', 'uses_fallback' => true]
            : ['min' => 1, 'max' => 3, 'label' => '1 a 3 días hábiles', 'uses_fallback' => false];
    }
}
