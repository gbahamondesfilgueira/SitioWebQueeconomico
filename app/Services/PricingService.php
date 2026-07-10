<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\PriceList;
use App\Models\Product;
use App\Models\ProductPack;
use App\Models\ProductVariant;
use App\Models\Promotion;
use App\Models\QuantityDiscount;
use App\Models\StockLevel;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class PricingService
{
    public function getBasePrice(Product $product, ?ProductVariant $variant = null, PriceList|string|null $priceList = null): array
    {
        $list = $priceList instanceof PriceList ? $priceList : $this->resolvePriceList($priceList);
        $trace = [];

        if ($list?->isCurrentlyActive()) {
            $item = $list->items()
                ->where('product_id', $product->id)
                ->when($variant, fn ($query) => $query->where('product_variant_id', $variant->id))
                ->where('is_active', true)
                ->latest()
                ->get()
                ->first(fn ($item) => $item->isCurrentlyActive());

            if (! $item && $variant) {
                $item = $list->items()
                    ->where('product_id', $product->id)
                    ->whereNull('product_variant_id')
                    ->where('is_active', true)
                    ->latest()
                    ->get()
                    ->first(fn ($item) => $item->isCurrentlyActive());
            }

            if ($item) {
                return ['price' => (float) $item->price, 'trace' => ["Lista {$list->code}: {$item->price}"]];
            }
        }

        $price = $variant?->regular_price ?? $product->regular_price ?? 0;
        $trace[] = $variant ? 'Precio base de variante' : 'Precio base de producto';

        return ['price' => (float) $price, 'trace' => $trace];
    }

    public function hasActiveSale(Product $product, ?ProductVariant $variant = null): bool
    {
        return $variant ? $variant->hasActiveSale() : $product->hasActiveSale();
    }

    public function getActiveSalePrice(Product $product, ?ProductVariant $variant = null): ?float
    {
        return $this->hasActiveSale($product, $variant) ? (float) ($variant?->sale_price ?? $product->sale_price) : null;
    }

    public function getBestPrice(Product $product, ?ProductVariant $variant = null, int $quantity = 1, ?User $user = null, PriceList|string|null $priceList = null, ?string $couponCode = null): array
    {
        $base = $this->getBasePrice($product, $variant, $priceList);
        $price = $base['price'];
        $trace = $base['trace'];

        if (($sale = $this->getActiveSalePrice($product, $variant)) !== null && $sale < $price) {
            $price = $sale;
            $trace[] = "Oferta activa: {$sale}";
        }

        $appliedPromotions = [];
        foreach ($this->getApplicablePromotions($product, $variant, $quantity, $user) as $promotion) {
            $newPrice = $this->applyPromotion($price, $promotion, compact('quantity'));
            if ($newPrice < $price) {
                $price = $newPrice;
                $appliedPromotions[] = $promotion->name;
                $trace[] = "Promoción {$promotion->name}: {$price}";
            }

            if (! $promotion->is_stackable) {
                break;
            }
        }

        $quantityResult = $this->applyQuantityDiscount($price, $product, $variant, $quantity);
        if ($quantityResult['price'] < $price) {
            $price = $quantityResult['price'];
            $trace[] = $quantityResult['trace'];
        }

        $couponResult = null;
        if ($couponCode) {
            $couponResult = $this->applyCoupon($couponCode, [
                'subtotal' => $price * $quantity,
                'quantity' => $quantity,
                'product' => $product,
                'variant' => $variant,
                'user' => $user,
            ]);

            if ($couponResult['valid']) {
                $unitDiscount = $quantity > 0 ? $couponResult['discount_amount'] / $quantity : 0;
                $price = max(0, $price - $unitDiscount);
                $trace[] = "Cupón {$couponCode}: -{$couponResult['discount_amount']}";
            } else {
                $trace[] = "Cupón {$couponCode} no aplicado: {$couponResult['reason']}";
            }
        }

        AuditLogger::record('used', 'pricing_calculator', "Cálculo de precio para {$product->name}");

        return [
            'base_price' => $base['price'],
            'sale_price' => $this->getActiveSalePrice($product, $variant),
            'final_price' => round($price, 2),
            'applied_promotions' => $appliedPromotions,
            'coupon' => $couponResult,
            'trace' => $trace,
        ];
    }

    public function getApplicablePromotions(Product $product, ?ProductVariant $variant = null, int $quantity = 1, ?User $user = null): Collection
    {
        return Promotion::query()
            ->with(['products', 'categories', 'brands', 'tags'])
            ->where('is_active', true)
            ->orderByDesc('priority')
            ->get()
            ->filter(function (Promotion $promotion) use ($product, $variant, $quantity, $user) {
                if (! $promotion->isCurrentlyActive()) return false;
                if ($promotion->usage_limit && $promotion->usage_count >= $promotion->usage_limit) return false;
                if ($promotion->min_quantity && $quantity < $promotion->min_quantity) return false;
                if ($promotion->customer_role && (! $user || ! $user->hasRole($promotion->customer_role))) return false;
                return $this->promotionTargetsProduct($promotion, $product, $variant);
            })
            ->values();
    }

    public function applyPromotion(float $price, Promotion $promotion, array $context = []): float
    {
        return match ($promotion->promotion_type) {
            'percentage_discount', 'quantity_discount', 'bundle_discount' => max(0, $price * (1 - ((float) $promotion->discount_percentage / 100))),
            'fixed_discount' => max(0, $price - (float) $promotion->discount_amount),
            'fixed_price' => max(0, (float) $promotion->fixed_price),
            default => $price,
        };
    }

    public function applyQuantityDiscount(float $price, Product $product, ?ProductVariant $variant = null, int $quantity = 1): array
    {
        $rule = QuantityDiscount::query()
            ->where('is_active', true)
            ->where('min_quantity', '<=', $quantity)
            ->where(fn ($query) => $query->whereNull('max_quantity')->orWhere('max_quantity', '>=', $quantity))
            ->get()
            ->filter(fn ($discount) => $discount->isCurrentlyActive() && $this->quantityDiscountTargetsProduct($discount, $product, $variant))
            ->sortByDesc('min_quantity')
            ->first();

        if (! $rule) {
            return ['price' => $price, 'trace' => 'Sin descuento por cantidad'];
        }

        $newPrice = match ($rule->discount_type) {
            'percentage' => max(0, $price * (1 - ((float) $rule->discount_value / 100))),
            'fixed' => max(0, $price - (float) $rule->discount_value),
            'fixed_price' => max(0, (float) $rule->discount_value),
        };

        return ['price' => round($newPrice, 2), 'trace' => "Descuento por cantidad {$rule->name}: {$newPrice}"];
    }

    public function validateCoupon(string $couponCode, array $cartContext = []): array
    {
        $coupon = Coupon::query()->where('code', strtoupper($couponCode))->first();
        if (! $coupon || ! $coupon->isCurrentlyActive()) return ['valid' => false, 'reason' => 'Cupón inválido o vencido'];
        if ($coupon->usage_limit && $coupon->usage_count >= $coupon->usage_limit) return ['valid' => false, 'reason' => 'Límite de uso alcanzado'];
        if ($coupon->min_subtotal && ($cartContext['subtotal'] ?? 0) < $coupon->min_subtotal) return ['valid' => false, 'reason' => 'Subtotal insuficiente'];
        if ($coupon->min_quantity && ($cartContext['quantity'] ?? 0) < $coupon->min_quantity) return ['valid' => false, 'reason' => 'Cantidad insuficiente'];

        $user = $cartContext['user'] ?? null;
        if ($user && $coupon->usage_limit_per_customer) {
            $uses = $coupon->usages()->where('user_id', $user->id)->count();
            if ($uses >= $coupon->usage_limit_per_customer) return ['valid' => false, 'reason' => 'Límite por cliente alcanzado'];
        }

        return ['valid' => true, 'reason' => null, 'coupon' => $coupon];
    }

    public function applyCoupon(string $couponCode, array $cartContext = []): array
    {
        $validation = $this->validateCoupon($couponCode, $cartContext);
        if (! $validation['valid']) return [...$validation, 'discount_amount' => 0];

        /** @var Coupon $coupon */
        $coupon = $validation['coupon'];
        $subtotal = (float) ($cartContext['subtotal'] ?? 0);
        $discount = match ($coupon->discount_type) {
            'percentage' => $subtotal * ((float) $coupon->discount_value / 100),
            'fixed' => (float) $coupon->discount_value,
            'free_shipping' => 0,
        };

        return [...$validation, 'discount_amount' => round(min($subtotal, $discount), 2)];
    }

    public function calculatePackPrice(ProductPack $productPack): float
    {
        return (float) $productPack->pack_price;
    }

    public function calculatePackAvailableStock(ProductPack $productPack, ?int $warehouseId = null): float
    {
        $productPack->loadMissing('items');
        if ($productPack->items->isEmpty()) return 0;

        return $productPack->items
            ->map(function ($item) use ($warehouseId) {
                $query = StockLevel::query()->where('product_id', $item->product_id)->where('product_variant_id', $item->product_variant_id);
                if ($warehouseId) $query->where('warehouse_id', $warehouseId);
                $available = $query->get()->sum('available_stock');
                return floor($available / max(1, (int) $item->quantity));
            })
            ->min() ?? 0;
    }

    private function resolvePriceList(PriceList|string|null $priceList): ?PriceList
    {
        if ($priceList instanceof PriceList) return $priceList;
        if (is_string($priceList) && $priceList !== '') return PriceList::query()->where('code', $priceList)->first();
        return PriceList::query()->where('is_default', true)->first();
    }

    private function promotionTargetsProduct(Promotion $promotion, Product $product, ?ProductVariant $variant): bool
    {
        if ($promotion->products->isEmpty() && $promotion->categories->isEmpty() && $promotion->brands->isEmpty() && $promotion->tags->isEmpty()) return true;
        if ($promotion->products->contains(fn ($p) => $p->id === $product->id && (! $p->pivot->product_variant_id || $p->pivot->product_variant_id === $variant?->id))) return true;
        if ($product->category_id && $promotion->categories->contains('id', $product->category_id)) return true;
        if ($product->brand_id && $promotion->brands->contains('id', $product->brand_id)) return true;
        if ($promotion->tags->isNotEmpty() && $product->tags()->whereIn('tags.id', $promotion->tags->pluck('id'))->exists()) return true;
        return false;
    }

    private function quantityDiscountTargetsProduct(QuantityDiscount $discount, Product $product, ?ProductVariant $variant): bool
    {
        if ($discount->product_variant_id) return $variant?->id === $discount->product_variant_id;
        if ($discount->product_id) return $product->id === $discount->product_id;
        if ($discount->category_id) return $product->category_id === $discount->category_id;
        if ($discount->brand_id) return $product->brand_id === $discount->brand_id;
        return true;
    }
}
