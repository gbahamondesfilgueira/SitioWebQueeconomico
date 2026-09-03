<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductPack;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;

class ProductDisplayService
{
    public function __construct(
        private PricingService $pricingService,
        private StockAllocationService $stockAllocationService,
        private DeliveryRegionService $deliveryRegionService,
    ) {}

    public function presentProduct(Product $product, ?ProductVariant $variant = null, int $quantity = 1): array
    {
        $product->loadMissing(['brand', 'category', 'images', 'tags', 'originCountry', 'variants.attributeValues.attribute', 'weightUnit', 'dimensionUnit']);
        $price = $this->pricingService->getBestPrice($product, $variant, $quantity, auth()->user());
        $regular = (float) ($variant?->regular_price ?? $product->regular_price ?? $price['base_price']);
        $final = (float) $price['final_price'];
        $availability = $this->availability($product, $variant);
        $stock = (int) $availability['available_stock'];
        $purchaseRegion = $this->currentPurchaseRegion();
        $canPurchase = $purchaseRegion !== null;
        $localRegion = $this->deliveryRegionService->currentLocalStockRegion();
        $localAvailability = $this->availabilityForRegion($product, $variant, $localRegion, true);
        $localStock = $localRegion ? (int) $localAvailability['available_stock'] : null;

        return [
            'product' => $product,
            'variant' => $variant,
            'price' => $price,
            'regular_price' => $regular,
            'final_price' => $final,
            'discount_percentage' => $regular > 0 && $final < $regular ? round((($regular - $final) / $regular) * 100) : 0,
            'stock' => $stock,
            'stock_label' => $canPurchase ? $this->stockLabel($stock) : $this->purchaseRequiredLabel(),
            'stock_class' => $canPurchase ? ($stock <= 0 ? 'text-bg-secondary' : ($stock <= 5 ? 'text-bg-warning' : 'text-bg-success')) : 'text-bg-info',
            'can_purchase' => $canPurchase,
            'stock_warehouse' => $availability['warehouse']?->name,
            'delivery_estimate' => $availability['estimate']['label'] ?? null,
            'uses_central_fallback' => (bool) ($availability['is_fallback'] ?? false),
            'destination_region' => $this->deliveryRegionService->label($purchaseRegion),
            'show_local_stock' => $localRegion !== null && $localRegion !== $purchaseRegion,
            'local_stock' => $localStock,
            'local_stock_label' => $localStock !== null ? $this->stockLabel($localStock) : null,
            'local_stock_class' => $localStock !== null ? $this->stockClass($localStock) : null,
            'local_stock_region' => $this->deliveryRegionService->label($localRegion),
            'local_stock_warehouse' => $localAvailability['warehouse']?->name,
            'image' => $this->imageUrl($variant?->image_path ?: $this->primaryImagePath($product)),
            'variants' => $this->variantsForFrontend($product),
        ];
    }

    public function presentPack(ProductPack $pack): array
    {
        $pack->loadMissing('items.product.images', 'items.variant');

        $normal = (float) ($pack->regular_price ?: $pack->items->sum(
            fn ($item) => (float) ($item->variant?->regular_price ?? $item->product?->regular_price ?? 0) * $item->quantity
        ));
        $price = (float) $pack->pack_price;
        $purchaseRegion = $this->currentPurchaseRegion();
        $availability = $purchaseRegion
            ? $this->stockAllocationService->packAvailability($pack, $purchaseRegion)
            : ['available_stock' => 0, 'warehouse' => null, 'estimate' => null, 'is_fallback' => false];
        $stock = (int) $availability['available_stock'];
        $canPurchase = $purchaseRegion !== null;
        $localRegion = $this->deliveryRegionService->currentLocalStockRegion();
        $localAvailability = $localRegion
            ? $this->stockAllocationService->packAvailability($pack, $localRegion)
            : $this->emptyAvailability();

        if ($localAvailability['is_fallback'] ?? false) {
            $localAvailability = $this->emptyAvailability();
        }

        $localStock = $localRegion ? (int) $localAvailability['available_stock'] : null;

        return [
            'pack' => $pack,
            'normal_price' => $normal,
            'pack_price' => $price,
            'saving' => max(0, $normal - $price),
            'saving_percentage' => $normal > 0 && $price < $normal ? round((($normal - $price) / $normal) * 100) : 0,
            'stock' => $stock,
            'stock_label' => $canPurchase ? $this->stockLabel($stock) : $this->purchaseRequiredLabel(),
            'can_purchase' => $canPurchase,
            'stock_warehouse' => $availability['warehouse']?->name,
            'delivery_estimate' => $availability['estimate']['label'] ?? null,
            'uses_central_fallback' => (bool) ($availability['is_fallback'] ?? false),
            'show_local_stock' => $localRegion !== null && $localRegion !== $purchaseRegion,
            'local_stock' => $localStock,
            'local_stock_label' => $localStock !== null ? $this->stockLabel($localStock) : null,
            'local_stock_class' => $localStock !== null ? $this->stockClass($localStock) : null,
            'local_stock_region' => $this->deliveryRegionService->label($localRegion),
            'local_stock_warehouse' => $localAvailability['warehouse']?->name,
            'image' => $this->imageUrl($pack->image_path),
        ];
    }

    public function availableStock(Product $product, ?ProductVariant $variant = null): int
    {
        return (int) $this->availability($product, $variant)['available_stock'];
    }

    public function stockLabel(int $stock): string
    {
        if ($stock <= 0) {
            return 'Sin stock';
        }

        if ($stock <= 5) {
            return 'Ultimas unidades';
        }

        return 'Disponible';
    }

    public function stockClass(int $stock): string
    {
        return $stock <= 0 ? 'text-bg-secondary' : ($stock <= 5 ? 'text-bg-warning' : 'text-bg-success');
    }

    public function imageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        return Storage::disk('public')->url($path);
    }

    public function variantsForFrontend(Product $product): array
    {
        $purchaseRegion = $this->currentPurchaseRegion();
        $localRegion = $this->deliveryRegionService->currentLocalStockRegion();

        return $product->variants
            ->where('is_active', true)
            ->map(function (ProductVariant $variant) use ($product, $purchaseRegion, $localRegion) {
                $price = $this->pricingService->getBestPrice($product, $variant, 1, auth()->user());
                $regular = (float) ($variant->regular_price ?? $product->regular_price ?? $price['base_price']);
                $availability = $this->availabilityForRegion($product, $variant, $purchaseRegion);
                $stock = (int) $availability['available_stock'];
                $canPurchase = $purchaseRegion !== null;
                $localAvailability = $this->availabilityForRegion($product, $variant, $localRegion, true);
                $localStock = $localRegion ? (int) $localAvailability['available_stock'] : null;

                return [
                    'id' => $variant->id,
                    'name' => $variant->name ?: $variant->attributeValues->pluck('value')->implode(' / '),
                    'sku' => $variant->sku,
                    'image' => $this->imageUrl($variant->image_path ?: $this->primaryImagePath($product)),
                    'stock' => $stock,
                    'final_price' => (float) $price['final_price'],
                    'regular_price' => $regular,
                    'stock_label' => $canPurchase ? $this->stockLabel($stock) : $this->purchaseRequiredLabel(),
                    'stock_warehouse' => $availability['warehouse']?->name,
                    'delivery_estimate' => $availability['estimate']['label'] ?? null,
                    'local_stock' => $localStock,
                    'local_stock_label' => $localStock !== null ? $this->stockLabel($localStock) : null,
                    'attributes' => $variant->attributeValues->map(fn ($value) => [
                        'attribute' => $value->attribute?->name,
                        'value' => $value->value,
                        'color_hex' => $value->color_hex,
                    ])->values(),
                ];
            })
            ->values()
            ->all();
    }

    private function primaryImagePath(Product $product): ?string
    {
        return $product->images->firstWhere('is_primary', true)?->image_path
            ?: $product->images->sortBy('sort_order')->first()?->image_path;
    }

    private function availability(Product $product, ?ProductVariant $variant): array
    {
        return $this->availabilityForRegion($product, $variant, $this->currentPurchaseRegion());
    }

    private function availabilityForRegion(Product $product, ?ProductVariant $variant, ?string $region, bool $localOnly = false): array
    {
        if (! $region) {
            return $this->emptyAvailability();
        }

        if (! $variant && $product->product_type === 'variable') {
            $availability = $product->variants
                ->where('is_active', true)
                ->map(fn (ProductVariant $item) => $this->availabilityForRegion($product, $item, $region, $localOnly))
                ->sortByDesc('available_stock')
                ->first()
                ?? $this->emptyAvailability();

            return $availability;
        }

        $availability = $this->stockAllocationService->productAvailability($product->id, $variant?->id, $region);

        return $localOnly && ($availability['is_fallback'] ?? false)
            ? $this->emptyAvailability()
            : $availability;
    }

    private function emptyAvailability(): array
    {
        return [
            'available_stock' => 0,
            'warehouse' => null,
            'location' => null,
            'estimate' => null,
            'is_fallback' => false,
        ];
    }

    private function currentPurchaseRegion(): ?string
    {
        if (! auth()->check()) {
            return null;
        }

        $region = $this->deliveryRegionService->currentRegion(auth()->user());

        return $region && array_key_exists($region, $this->deliveryRegionService->regions()) ? $region : null;
    }

    private function purchaseRequiredLabel(): string
    {
        return auth()->check() ? 'Configura tu dirección para consultar stock' : 'Inicia sesión para consultar stock';
    }
}
