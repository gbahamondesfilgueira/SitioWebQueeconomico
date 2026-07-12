<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductPack;
use App\Models\ProductVariant;
use App\Models\StockLevel;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Storage;

class ProductDisplayService
{
    public function __construct(private PricingService $pricingService) {}

    public function presentProduct(Product $product, ?ProductVariant $variant = null, int $quantity = 1): array
    {
        $product->loadMissing(['brand', 'category', 'images', 'tags', 'originCountry', 'variants.attributeValues.attribute', 'weightUnit', 'dimensionUnit']);
        $price = $this->pricingService->getBestPrice($product, $variant, $quantity, auth()->user());
        $regular = (float) ($variant?->regular_price ?? $product->regular_price ?? $price['base_price']);
        $final = (float) $price['final_price'];
        $stock = $this->availableStock($product, $variant);

        return [
            'product' => $product,
            'variant' => $variant,
            'price' => $price,
            'regular_price' => $regular,
            'final_price' => $final,
            'discount_percentage' => $regular > 0 && $final < $regular ? round((($regular - $final) / $regular) * 100) : 0,
            'stock' => $stock,
            'stock_label' => $this->stockLabel($stock),
            'stock_class' => $stock <= 0 ? 'text-bg-secondary' : ($stock <= 5 ? 'text-bg-warning' : 'text-bg-success'),
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
        $stock = (int) $pack->getAvailableStock($this->ecommerceWarehouseId());

        return [
            'pack' => $pack,
            'normal_price' => $normal,
            'pack_price' => $price,
            'saving' => max(0, $normal - $price),
            'saving_percentage' => $normal > 0 && $price < $normal ? round((($normal - $price) / $normal) * 100) : 0,
            'stock' => $stock,
            'stock_label' => $this->stockLabel($stock),
            'image' => $this->imageUrl($pack->image_path),
        ];
    }

    public function availableStock(Product $product, ?ProductVariant $variant = null): int
    {
        $warehouseId = $this->ecommerceWarehouseId();

        if (! $variant && $product->product_type === 'variable') {
            return (int) StockLevel::query()
                ->where('product_id', $product->id)
                ->whereNotNull('product_variant_id')
                ->when($warehouseId, fn ($query) => $query->where('warehouse_id', $warehouseId))
                ->get()
                ->sum('available_stock');
        }

        return (int) StockLevel::query()
            ->where('product_id', $product->id)
            ->where('product_variant_id', $variant?->id)
            ->when($warehouseId, fn ($query) => $query->where('warehouse_id', $warehouseId))
            ->get()
            ->sum('available_stock');
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
        return $product->variants
            ->where('is_active', true)
            ->map(function (ProductVariant $variant) use ($product) {
                $price = $this->pricingService->getBestPrice($product, $variant, 1, auth()->user());
                $regular = (float) ($variant->regular_price ?? $product->regular_price ?? $price['base_price']);
                $stock = $this->availableStock($product, $variant);

                return [
                    'id' => $variant->id,
                    'name' => $variant->name ?: $variant->attributeValues->pluck('value')->implode(' / '),
                    'sku' => $variant->sku,
                    'image' => $this->imageUrl($variant->image_path ?: $this->primaryImagePath($product)),
                    'stock' => $stock,
                    'final_price' => (float) $price['final_price'],
                    'regular_price' => $regular,
                    'stock_label' => $this->stockLabel($stock),
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

    private function ecommerceWarehouseId(): ?int
    {
        return Warehouse::query()->where('code', 'ECOM')->where('is_active', true)->value('id')
            ?: Warehouse::query()->where('is_active', true)->value('id');
    }
}
