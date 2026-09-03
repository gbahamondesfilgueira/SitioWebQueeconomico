<?php

namespace App\Services;

use App\Models\ProductPack;
use App\Models\StockLevel;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Builder;

class StockAllocationService
{
    public function __construct(private DeliveryRegionService $regions) {}

    public function findProductAllocation(int $productId, ?int $variantId, int $quantity, ?string $destinationRegion, bool $lock = false): ?array
    {
        foreach ($this->regions->warehouseCandidates($destinationRegion) as $warehouse) {
            $level = $this->availableLevelQuery($warehouse, $productId, $variantId, $quantity)
                ->when($lock, fn (Builder $query) => $query->lockForUpdate())
                ->first();

            if ($level) {
                return $this->presentAllocation($level, $warehouse, $destinationRegion);
            }
        }

        return null;
    }

    public function findPackAllocations(ProductPack $pack, int $quantity, ?string $destinationRegion, bool $lock = false): ?array
    {
        $pack->loadMissing('items');

        foreach ($this->regions->warehouseCandidates($destinationRegion) as $warehouse) {
            $allocations = [];

            foreach ($pack->items as $component) {
                $required = (int) $component->quantity * $quantity;
                $level = $this->availableLevelQuery($warehouse, $component->product_id, $component->product_variant_id, $required)
                    ->when($lock, fn (Builder $query) => $query->lockForUpdate())
                    ->first();

                if (! $level) {
                    $allocations = [];
                    break;
                }

                $allocations[] = $this->presentAllocation($level, $warehouse, $destinationRegion) + [
                    'required_quantity' => $required,
                    'pack_item_id' => $component->id,
                ];
            }

            if (count($allocations) === $pack->items->count()) {
                return $allocations;
            }
        }

        return null;
    }

    public function productAvailability(int $productId, ?int $variantId, ?string $destinationRegion): array
    {
        return $this->findProductAllocation($productId, $variantId, 1, $destinationRegion)
            ?? ['available_stock' => 0, 'warehouse' => null, 'location' => null, 'estimate' => null, 'is_fallback' => false];
    }

    public function packAvailability(ProductPack $pack, ?string $destinationRegion): array
    {
        $pack->loadMissing('items');

        foreach ($this->regions->warehouseCandidates($destinationRegion) as $warehouse) {
            $availablePacks = null;
            $locations = [];

            foreach ($pack->items as $component) {
                $level = $this->availableLevelQuery($warehouse, $component->product_id, $component->product_variant_id, 1)->first();
                if (! $level) {
                    $availablePacks = 0;
                    break;
                }
                $componentPacks = intdiv($level->available_stock, max(1, (int) $component->quantity));
                $availablePacks = $availablePacks === null ? $componentPacks : min($availablePacks, $componentPacks);
                $locations[] = $level->location;
            }

            if (($availablePacks ?? 0) > 0) {
                return [
                    'available_stock' => $availablePacks,
                    'warehouse' => $warehouse,
                    'locations' => collect($locations),
                    'estimate' => $this->regions->deliveryEstimate($destinationRegion, collect([$warehouse])),
                    'is_fallback' => $this->regions->normalize($warehouse->region) !== $this->regions->normalize($destinationRegion),
                ];
            }
        }

        return ['available_stock' => 0, 'warehouse' => null, 'locations' => collect(), 'estimate' => null, 'is_fallback' => false];
    }

    private function availableLevelQuery(Warehouse $warehouse, int $productId, ?int $variantId, int $quantity): Builder
    {
        return StockLevel::query()
            ->with(['warehouse', 'location'])
            ->where('warehouse_id', $warehouse->id)
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->whereRaw('(physical_stock - reserved_stock) >= ?', [$quantity])
            ->where(function (Builder $query) {
                $query->whereNull('warehouse_location_id')
                    ->orWhereHas('location', fn (Builder $location) => $location
                        ->where('is_active', true)
                        ->where('is_sellable', true));
            })
            ->orderByRaw('warehouse_location_id is null')
            ->orderByRaw('(physical_stock - reserved_stock) desc')
            ->orderBy('warehouse_location_id');
    }

    private function presentAllocation(StockLevel $level, Warehouse $warehouse, ?string $destinationRegion): array
    {
        return [
            'stock_level_id' => $level->id,
            'warehouse_id' => $warehouse->id,
            'warehouse_location_id' => $level->warehouse_location_id,
            'available_stock' => $level->available_stock,
            'warehouse' => $warehouse,
            'location' => $level->location,
            'estimate' => $this->regions->deliveryEstimate($destinationRegion, collect([$warehouse])),
            'is_fallback' => $this->regions->normalize($warehouse->region) !== $this->regions->normalize($destinationRegion),
        ];
    }
}
