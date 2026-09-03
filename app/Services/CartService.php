<?php

namespace App\Services;

use App\Models\CartCoupon;
use App\Models\CartItem;
use App\Models\CartSession;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductPack;
use App\Models\ProductVariant;
use App\Models\StockReservation;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartService
{
    public const RESERVATION_MINUTES = 30;

    public const SHIPPING_ESTIMATE = 3990;

    public const TAX_RATE = 0.19;

    public function __construct(
        private PricingService $pricingService,
        private InventoryService $inventoryService,
        private StockAllocationService $stockAllocationService,
        private DeliveryRegionService $deliveryRegionService,
    ) {}

    public function getOrCreateCart(?User $user = null, ?string $sessionId = null): CartSession
    {
        $sessionId ??= session()->getId();
        $accountRegion = $user ? $this->deliveryRegionService->currentRegion($user) : null;

        $cart = CartSession::query()
            ->where('status', 'active')
            ->when($user, fn ($q) => $q->where('user_id', $user->id), fn ($q) => $q->where('session_id', $sessionId))
            ->latest()
            ->first();

        if (! $cart) {
            $cart = CartSession::query()->create([
                'user_id' => $user?->id,
                'session_id' => $user ? null : $sessionId,
                'status' => 'active',
                'destination_region_code' => $accountRegion,
                'expires_at' => now()->addMinutes(self::RESERVATION_MINUTES),
            ]);
        } elseif ($user && $accountRegion && $cart->destination_region_code !== $accountRegion) {
            $cart = $cart->items()->exists()
                ? $this->reallocateForRegion($cart, $accountRegion)
                : tap($cart)->update(['destination_region_code' => $accountRegion]);
        }

        return $cart->load(['items.product.images', 'items.variant', 'items.pack.items.product', 'coupons.coupon', 'shippingMethod', 'paymentMethod', 'addresses']);
    }

    public function addProduct(CartSession $cart, int $productId, ?int $variantId = null, int $quantity = 1): CartItem
    {
        return DB::transaction(function () use ($cart, $productId, $variantId, $quantity) {
            $this->assertActiveCart($cart);
            $this->assertPositiveQuantity($quantity);

            $product = Product::query()->whereKey($productId)->where('is_active', true)->where('is_visible', true)->firstOrFail();
            $variant = $variantId ? ProductVariant::query()->whereKey($variantId)->where('product_id', $product->id)->where('is_active', true)->firstOrFail() : null;
            if ($product->product_type === 'variable' && ! $variant) {
                throw ValidationException::withMessages(['product_variant_id' => 'Debes seleccionar una variante.']);
            }

            $existing = $cart->items()->where('item_type', 'product')->where('product_id', $product->id)->where('product_variant_id', $variant?->id)->first();
            $newQuantity = ($existing?->quantity ?? 0) + $quantity;

            $item = $existing ?: $cart->items()->make(['item_type' => 'product', 'product_id' => $product->id, 'product_variant_id' => $variant?->id]);
            $item->quantity = $newQuantity;
            $this->priceProductItem($item, $product, $variant);
            $item->save();
            $this->refreshReservation($item);
            $this->recalculateCart($cart);

            AuditLogger::record('added', 'cart', "Producto agregado al carrito: {$product->name}");

            return $item->refresh();
        });
    }

    public function addPack(CartSession $cart, int $packId, int $quantity = 1): CartItem
    {
        return DB::transaction(function () use ($cart, $packId, $quantity) {
            $this->assertActiveCart($cart);
            $this->assertPositiveQuantity($quantity);

            $pack = ProductPack::query()->with('items')->whereKey($packId)->where('is_active', true)->where('is_visible', true)->firstOrFail();
            if (! $pack->isCurrentlyActive() || $pack->items->isEmpty()) {
                throw ValidationException::withMessages(['pack' => 'Pack no disponible.']);
            }
            if (! $this->stockAllocationService->findPackAllocations($pack, $quantity, $cart->destination_region_code)) {
                throw ValidationException::withMessages(['quantity' => 'Stock insuficiente para el pack.']);
            }

            $existing = $cart->items()->where('item_type', 'pack')->where('product_pack_id', $pack->id)->first();
            $item = $existing ?: $cart->items()->make(['item_type' => 'pack', 'product_pack_id' => $pack->id]);
            $item->quantity = ($existing?->quantity ?? 0) + $quantity;
            $this->pricePackItem($item, $pack);
            $item->save();
            $this->refreshPackReservations($item);
            $this->recalculateCart($cart);

            AuditLogger::record('added', 'cart', "Pack agregado al carrito: {$pack->name}");

            return $item->refresh();
        });
    }

    public function updateItemQuantity(CartItem $cartItem, int $quantity): CartItem
    {
        return DB::transaction(function () use ($cartItem, $quantity) {
            $this->assertPositiveQuantity($quantity);
            $cartItem->quantity = $quantity;
            if ($cartItem->item_type === 'product') {
                $this->priceProductItem($cartItem, $cartItem->product, $cartItem->variant);
                $cartItem->save();
                $this->refreshReservation($cartItem);
            } else {
                if (! $this->stockAllocationService->findPackAllocations($cartItem->pack, $quantity, $cartItem->cart->destination_region_code)) {
                    throw ValidationException::withMessages(['quantity' => 'Stock insuficiente para el pack.']);
                }
                $this->pricePackItem($cartItem, $cartItem->pack);
                $cartItem->save();
                $this->refreshPackReservations($cartItem);
            }
            $this->recalculateCart($cartItem->cart);
            AuditLogger::record('updated', 'cart', "Cantidad actualizada item #{$cartItem->id}");

            return $cartItem->refresh();
        });
    }

    public function removeItem(CartItem $cartItem): void
    {
        DB::transaction(function () use ($cartItem) {
            if ($cartItem->reservation && $cartItem->reservation->status === 'active') {
                $this->inventoryService->releaseReservation($cartItem->reservation);
            }
            $this->releasePackReservations($cartItem);
            $cart = $cartItem->cart;
            $cartItem->delete();
            $this->recalculateCart($cart);
            AuditLogger::record('removed', 'cart', 'Item eliminado del carrito.');
        });
    }

    public function clearCart(CartSession $cart): void
    {
        DB::transaction(function () use ($cart) {
            $this->releaseReservations($cart);
            $cart->items()->delete();
            $cart->coupons()->delete();
            AuditLogger::record('cleared', 'cart', "Carrito vaciado #{$cart->id}");
        });
    }

    public function recalculateCart(CartSession $cart): CartSession
    {
        $cart->load('items.product', 'items.variant', 'items.pack', 'coupons.coupon');
        foreach ($cart->items as $item) {
            if ($item->item_type === 'product') {
                $this->priceProductItem($item, $item->product, $item->variant);
            } else {
                $this->pricePackItem($item, $item->pack);
            }
            $item->save();
        }

        foreach ($cart->coupons as $cartCoupon) {
            $this->recalculateCoupon($cart, $cartCoupon);
        }

        $cart->update(['expires_at' => now()->addMinutes(self::RESERVATION_MINUTES)]);

        return $cart->refresh();
    }

    public function applyCoupon(CartSession $cart, string $couponCode): CartCoupon
    {
        return DB::transaction(function () use ($cart, $couponCode) {
            $summary = $this->getCartSummary($cart);
            $result = $this->pricingService->applyCoupon($couponCode, [
                'subtotal' => $summary['subtotal'],
                'quantity' => $cart->items()->sum('quantity'),
                'user' => $cart->user,
            ]);

            if (! $result['valid']) {
                throw ValidationException::withMessages(['coupon' => $result['reason']]);
            }

            /** @var Coupon $coupon */
            $coupon = $result['coupon'];
            if ($cart->coupons()->where('coupon_id', $coupon->id)->exists()) {
                throw ValidationException::withMessages(['coupon' => 'El cupón ya está aplicado.']);
            }

            $cartCoupon = $cart->coupons()->create([
                'coupon_id' => $coupon->id,
                'coupon_code' => $coupon->code,
                'discount_amount' => $result['discount_amount'],
                'applied_at' => now(),
            ]);

            AuditLogger::record('applied', 'coupons', "Cupón aplicado: {$coupon->code}");

            return $cartCoupon;
        });
    }

    public function removeCoupon(CartSession $cart): void
    {
        $cart->coupons()->delete();
        AuditLogger::record('removed', 'coupons', "Cupón quitado del carrito #{$cart->id}");
    }

    public function releaseReservations(CartSession $cart): void
    {
        foreach ($cart->items()->with('reservation')->get() as $item) {
            if ($item->reservation && $item->reservation->status === 'active') {
                $this->inventoryService->releaseReservation($item->reservation);
            }
            $this->releasePackReservations($item);
        }
    }

    public function reallocateForRegion(CartSession $cart, string $region): CartSession
    {
        return DB::transaction(function () use ($cart, $region) {
            $cart = CartSession::query()->whereKey($cart->id)->lockForUpdate()->firstOrFail();
            $this->assertActiveCart($cart);
            $region = $this->deliveryRegionService->normalize($region);

            $this->releaseReservations($cart);
            $cart->update(['destination_region_code' => $region]);

            foreach ($cart->items()->with(['product', 'variant', 'pack.items'])->get() as $item) {
                $item->unsetRelation('reservation');
                if ($item->item_type === 'pack') {
                    $this->refreshPackReservations($item);
                } else {
                    $this->refreshReservation($item);
                }
            }

            $cart->shippingMethod()->delete();

            return $cart->refresh();
        });
    }

    public function deliveryEstimate(CartSession $cart): array
    {
        $warehouses = $this->fulfillmentWarehouses($cart);

        if ($warehouses->isEmpty()) {
            $central = $this->deliveryRegionService->warehouseCandidates($cart->destination_region_code)->first();
            $warehouses = $central ? collect([$central]) : collect();
        }

        return $this->deliveryRegionService->deliveryEstimate($cart->destination_region_code, $warehouses);
    }

    public function fulfillmentWarehouses(CartSession $cart): Collection
    {
        $itemIds = $cart->items()->pluck('id');

        return Warehouse::query()
            ->whereIn('id', StockReservation::query()
                ->where('reference_type', CartItem::class)
                ->whereIn('reference_id', $itemIds)
                ->whereIn('status', ['active', 'consumed'])
                ->select('warehouse_id'))
            ->get();
    }

    public function expireOldCarts(): int
    {
        $count = 0;
        CartSession::query()->where('status', 'active')->whereNotNull('expires_at')->where('expires_at', '<', now())->chunkById(50, function ($carts) use (&$count) {
            foreach ($carts as $cart) {
                DB::transaction(function () use ($cart, &$count) {
                    $this->releaseReservations($cart);
                    $cart->update(['status' => 'expired']);
                    AuditLogger::record('expired', 'cart', "Carrito expirado #{$cart->id}");
                    $count++;
                });
            }
        });

        return $count;
    }

    public function getCartSummary(CartSession $cart): array
    {
        $cart->load('items', 'coupons', 'shippingMethod');
        $subtotalRegular = (float) $cart->items->sum(fn ($item) => (float) ($item->regular_price ?? $item->unit_price) * $item->quantity);
        $subtotal = (float) $cart->items->sum('line_subtotal');
        $itemDiscounts = (float) $cart->items->sum('line_discount');
        $couponDiscount = (float) $cart->coupons->sum('discount_amount');
        $shipping = $cart->shippingMethod ? (float) $cart->shippingMethod->estimated_price : ($cart->items->isEmpty() ? 0 : self::SHIPPING_ESTIMATE);
        $taxable = max(0, $subtotal - $couponDiscount);
        $tax = round($taxable * self::TAX_RATE, 2);
        $grand = max(0, $taxable + $tax + $shipping);

        return compact('subtotalRegular', 'subtotal', 'itemDiscounts', 'couponDiscount') + [
            'shippingEstimate' => $shipping,
            'taxTotal' => $tax,
            'grandTotal' => $grand,
        ];
    }

    private function priceProductItem(CartItem $item, Product $product, ?ProductVariant $variant): void
    {
        $price = $this->pricingService->getBestPrice($product, $variant, (int) $item->quantity, $item->cart?->user);
        $regular = (float) ($variant?->regular_price ?? $product->regular_price ?? $price['base_price']);
        $final = (float) $price['final_price'];
        $item->fill([
            'unit_price' => $price['base_price'],
            'regular_price' => $regular,
            'discount_amount' => max(0, $regular - $final),
            'final_unit_price' => $final,
            'line_subtotal' => $final * $item->quantity,
            'line_discount' => max(0, ($regular - $final) * $item->quantity),
            'line_total' => $final * $item->quantity,
            'applied_rules' => $price['trace'],
        ]);
    }

    private function pricePackItem(CartItem $item, ProductPack $pack): void
    {
        $regular = (float) ($pack->regular_price ?: $pack->items()->with('product', 'variant')->get()->sum(fn ($packItem) => (float) ($packItem->variant?->regular_price ?? $packItem->product?->regular_price ?? 0) * $packItem->quantity));
        $final = (float) $pack->pack_price;
        $item->fill([
            'unit_price' => $final,
            'regular_price' => $regular,
            'discount_amount' => max(0, $regular - $final),
            'final_unit_price' => $final,
            'line_subtotal' => $final * $item->quantity,
            'line_discount' => max(0, ($regular - $final) * $item->quantity),
            'line_total' => $final * $item->quantity,
            'applied_rules' => ['Precio pack'],
        ]);
    }

    private function refreshReservation(CartItem $item): void
    {
        if ($item->reservation && $item->reservation->status === 'active') {
            $this->inventoryService->releaseReservation($item->reservation);
        }

        $allocation = $this->stockAllocationService->findProductAllocation(
            $item->product_id,
            $item->product_variant_id,
            (int) $item->quantity,
            $item->cart->destination_region_code,
            true,
        );

        if (! $allocation) {
            throw ValidationException::withMessages(['quantity' => 'Stock insuficiente en la bodega regional y en la bodega central.']);
        }

        $reservation = $this->inventoryService->reserveStockLevel(
            $allocation['stock_level_id'],
            (float) $item->quantity,
            CartItem::class,
            $item->id,
            now()->addMinutes(self::RESERVATION_MINUTES),
        );

        $item->forceFill(['stock_reservation_id' => $reservation->id])->save();
    }

    private function refreshPackReservations(CartItem $item): void
    {
        $this->releasePackReservations($item);
        $item->pack->loadMissing('items');

        $allocations = $this->stockAllocationService->findPackAllocations(
            $item->pack,
            (int) $item->quantity,
            $item->cart->destination_region_code,
            true,
        );

        if (! $allocations) {
            throw ValidationException::withMessages(['quantity' => 'Stock insuficiente para armar el pack en una sola bodega.']);
        }

        foreach ($allocations as $allocation) {
            $this->inventoryService->reserveStockLevel(
                $allocation['stock_level_id'],
                (float) $allocation['required_quantity'],
                CartItem::class,
                $item->id,
                now()->addMinutes(self::RESERVATION_MINUTES),
            );
        }
    }

    private function releasePackReservations(CartItem $item): void
    {
        if ($item->item_type !== 'pack' || ! $item->exists) {
            return;
        }

        StockReservation::query()
            ->where('reference_type', CartItem::class)
            ->where('reference_id', $item->id)
            ->where('status', 'active')
            ->get()
            ->each(fn (StockReservation $reservation) => $this->inventoryService->releaseReservation($reservation));
    }

    private function recalculateCoupon(CartSession $cart, CartCoupon $cartCoupon): void
    {
        $summary = $this->getCartSummary($cart);
        $result = $this->pricingService->applyCoupon($cartCoupon->coupon_code, [
            'subtotal' => $summary['subtotal'],
            'quantity' => $cart->items()->sum('quantity'),
            'user' => $cart->user,
        ]);

        if (! $result['valid']) {
            $cartCoupon->delete();

            return;
        }

        $cartCoupon->update(['discount_amount' => $result['discount_amount'], 'applied_at' => now()]);
    }

    private function assertActiveCart(CartSession $cart): void
    {
        if ($cart->status !== 'active') {
            throw ValidationException::withMessages(['cart' => 'El carrito no está activo.']);
        }
    }

    private function assertPositiveQuantity(int $quantity): void
    {
        if ($quantity < 1) {
            throw ValidationException::withMessages(['quantity' => 'La cantidad debe ser mayor a cero.']);
        }
    }
}
