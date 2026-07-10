<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CustomerProfile;
use App\Models\Order;
use App\Models\PosCart;
use App\Models\PosCartItem;
use App\Models\PosCartPayment;
use App\Models\PosManualDiscount;
use App\Models\PosPaymentMethod;
use App\Models\PosQuote;
use App\Models\PosReservation;
use App\Models\PosTerminal;
use App\Models\Product;
use App\Models\ProductPack;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\StockReservation;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PosService
{
    public const TAX_RATE = 0.19;

    public function __construct(
        private PricingService $pricingService,
        private InventoryService $inventoryService,
        private OrderService $orderService,
        private CashRegisterService $cashRegisterService,
    ) {}

    public function getActiveCart(int $terminalId, int $userId): PosCart
    {
        $this->assertTerminalActive($terminalId);

        return PosCart::query()
            ->with($this->cartRelations())
            ->firstOrCreate([
                'pos_terminal_id' => $terminalId,
                'user_id' => $userId,
                'status' => 'active',
            ], [
                'currency' => Setting::current()->currency ?? 'CLP',
            ]);
    }

    public function createCart(int $terminalId, int $userId): PosCart
    {
        $this->assertTerminalActive($terminalId);

        return PosCart::query()->create([
            'pos_terminal_id' => $terminalId,
            'user_id' => $userId,
            'status' => 'active',
            'currency' => Setting::current()->currency ?? 'CLP',
        ])->load($this->cartRelations());
    }

    public function addProductById(PosCart $cart, int $productId, ?int $variantId = null, int $quantity = 1): PosCartItem
    {
        return DB::transaction(function () use ($cart, $productId, $variantId, $quantity) {
            $this->assertCartActive($cart);
            $this->assertQuantity($quantity);
            $terminal = $cart->terminal()->firstOrFail();
            $product = Product::query()->whereKey($productId)->where('is_active', true)->firstOrFail();
            $variant = $variantId ? ProductVariant::query()->whereKey($variantId)->where('product_id', $product->id)->where('is_active', true)->firstOrFail() : null;
            $available = $this->inventoryService->getAvailableStock($terminal->warehouse_id, $product->id, $variant?->id);
            if ($available < $quantity) {
                AuditLogger::record('stock_error', 'pos', "Stock insuficiente para {$product->name}");
                throw ValidationException::withMessages(['quantity' => 'Stock insuficiente en la bodega del POS.']);
            }

            $price = $this->pricingService->getBestPrice($product, $variant, $quantity, $cart->user);
            $item = $cart->items()->create([
                'item_type' => 'product',
                'product_id' => $product->id,
                'product_variant_id' => $variant?->id,
                'quantity' => $quantity,
                'regular_unit_price' => $price['base_price'],
                'final_unit_price' => $price['final_price'],
                'line_subtotal' => $price['base_price'] * $quantity,
                'line_discount' => max(0, ($price['base_price'] - $price['final_price']) * $quantity),
                'line_tax' => round(($price['final_price'] * $quantity) * self::TAX_RATE, 2),
                'line_total' => $price['final_price'] * $quantity,
                'applied_rules' => $price['trace'],
            ]);

            $reservation = $this->inventoryService->reserveStock(
                $terminal->warehouse_id,
                $product->id,
                $variant?->id,
                $quantity,
                PosCartItem::class,
                $item->id,
                now()->addMinutes($this->reservationMinutes()),
            );
            $item->update(['stock_reservation_id' => $reservation->id]);
            $this->recalculateCart($cart);
            AuditLogger::record('product_added', 'pos_carts', "Producto agregado al POS: {$product->name}");

            return $item->refresh();
        });
    }

    public function addProductByBarcode(PosCart $cart, string $barcode, int $quantity = 1): PosCartItem
    {
        $barcode = trim($barcode);
        $product = Product::query()->where('barcode', $barcode)->where('is_active', true)->first();
        if ($product) {
            return $this->addProductById($cart, $product->id, null, $quantity);
        }

        $variant = ProductVariant::query()->with('product')->where('barcode', $barcode)->where('is_active', true)->first();
        if ($variant) {
            return $this->addProductById($cart, $variant->product_id, $variant->id, $quantity);
        }

        $pack = ProductPack::query()->where('barcode', $barcode)->where('is_active', true)->first();
        if ($pack) {
            return $this->addPack($cart, $pack->id, $quantity);
        }

        throw ValidationException::withMessages(['barcode' => 'No se encontró un producto con ese código de barras.']);
    }

    public function addPack(PosCart $cart, int $packId, int $quantity = 1): PosCartItem
    {
        return DB::transaction(function () use ($cart, $packId, $quantity) {
            $this->assertCartActive($cart);
            $this->assertQuantity($quantity);
            $terminal = $cart->terminal()->firstOrFail();
            $pack = ProductPack::query()->with('items.product', 'items.variant')->whereKey($packId)->where('is_active', true)->firstOrFail();
            if (! $pack->isCurrentlyActive() || ! $pack->is_visible) {
                throw ValidationException::withMessages(['pack' => 'El pack no está vigente o visible.']);
            }
            if ($this->pricingService->calculatePackAvailableStock($pack, $terminal->warehouse_id) < $quantity) {
                throw ValidationException::withMessages(['quantity' => 'Stock insuficiente para los componentes del pack.']);
            }

            $unitPrice = $this->pricingService->calculatePackPrice($pack);
            $regular = (float) ($pack->regular_price ?: $pack->items->sum(fn ($item) => (float) ($item->variant?->regular_price ?? $item->product?->regular_price ?? 0) * $item->quantity));
            $item = $cart->items()->create([
                'item_type' => 'pack',
                'product_pack_id' => $pack->id,
                'quantity' => $quantity,
                'regular_unit_price' => $regular,
                'final_unit_price' => $unitPrice,
                'line_subtotal' => $regular * $quantity,
                'line_discount' => max(0, ($regular - $unitPrice) * $quantity),
                'line_tax' => round(($unitPrice * $quantity) * self::TAX_RATE, 2),
                'line_total' => $unitPrice * $quantity,
                'applied_rules' => ['Precio pack vigente'],
            ]);

            foreach ($pack->items as $component) {
                $this->inventoryService->reserveStock(
                    $terminal->warehouse_id,
                    $component->product_id,
                    $component->product_variant_id,
                    $component->quantity * $quantity,
                    PosCartItem::class,
                    $item->id,
                    now()->addMinutes($this->reservationMinutes()),
                );
            }

            $this->recalculateCart($cart);
            AuditLogger::record('pack_added', 'pos_carts', "Pack agregado al POS: {$pack->name}");

            return $item->refresh();
        });
    }

    public function updateQuantity(PosCartItem $cartItem, int $quantity): PosCartItem
    {
        return DB::transaction(function () use ($cartItem, $quantity) {
            $this->assertQuantity($quantity);
            $cart = $cartItem->cart()->with('terminal')->firstOrFail();
            $this->releaseItemReservations($cartItem);
            $cartItem->delete();
            $newItem = $cartItem->item_type === 'pack'
                ? $this->addPack($cart, (int) $cartItem->product_pack_id, $quantity)
                : $this->addProductById($cart, (int) $cartItem->product_id, $cartItem->product_variant_id, $quantity);
            AuditLogger::record('quantity_updated', 'pos_carts', 'Cantidad modificada en POS');

            return $newItem;
        });
    }

    public function removeItem(PosCartItem $cartItem): void
    {
        DB::transaction(function () use ($cartItem): void {
            $cart = $cartItem->cart()->firstOrFail();
            $this->releaseItemReservations($cartItem);
            $cartItem->delete();
            $this->recalculateCart($cart);
            AuditLogger::record('product_removed', 'pos_carts', 'Producto eliminado del POS');
        });
    }

    public function clearCart(PosCart $cart): void
    {
        DB::transaction(function () use ($cart): void {
            foreach ($cart->items as $item) {
                $this->releaseItemReservations($item);
            }
            $cart->items()->delete();
            $cart->coupons()->delete();
            $cart->payments()->delete();
            $cart->discounts()->delete();
            $this->recalculateCart($cart);
            AuditLogger::record('cleared', 'pos_carts', 'Carrito POS vaciado');
        });
    }

    public function applyCoupon(PosCart $cart, string $couponCode): void
    {
        $summary = $this->getCartSummary($cart);
        $result = $this->pricingService->applyCoupon($couponCode, ['subtotal' => $summary['subtotal'], 'quantity' => $summary['quantity'], 'user' => $cart->user]);
        if (! $result['valid']) {
            throw ValidationException::withMessages(['coupon' => $result['reason']]);
        }
        /** @var Coupon $coupon */
        $coupon = $result['coupon'];
        $cart->coupons()->updateOrCreate(['coupon_id' => $coupon->id], [
            'coupon_code' => $coupon->code,
            'discount_amount' => $result['discount_amount'],
            'applied_at' => now(),
        ]);
        $this->recalculateCart($cart);
        AuditLogger::record('coupon_applied', 'pos_carts', "Cupón POS aplicado: {$coupon->code}");
    }

    public function removeCoupon(PosCart $cart, string $couponCode): void
    {
        $cart->coupons()->where('coupon_code', strtoupper($couponCode))->delete();
        $this->recalculateCart($cart);
        AuditLogger::record('coupon_removed', 'pos_carts', "Cupón POS eliminado: {$couponCode}");
    }

    public function requestManualDiscount(PosCart $cart, array $data): PosManualDiscount
    {
        $discount = DB::transaction(function () use ($cart, $data) {
            $base = $this->discountBase($cart, $data['pos_cart_item_id'] ?? null);
            $amount = $this->calculateManualDiscount($base, $data['discount_type'], (float) $data['discount_value']);
            $limit = (float) Setting::current()->pos_manual_discount_without_approval;
            $autoApproved = $data['discount_type'] === 'percentage' && (float) $data['discount_value'] <= $limit;

            $discount = $cart->discounts()->create([
                'pos_cart_item_id' => $data['pos_cart_item_id'] ?? null,
                'discount_type' => $data['discount_type'],
                'discount_value' => $data['discount_value'],
                'discount_amount' => min($base, $amount),
                'reason' => $data['reason'],
                'requested_by' => Auth::id(),
                'approved_by' => $autoApproved ? Auth::id() : null,
                'approved_at' => $autoApproved ? now() : null,
                'status' => $autoApproved ? 'approved' : 'pending',
            ]);

            $this->recalculateCart($cart);
            AuditLogger::record('discount_requested', 'pos_discounts', "Descuento POS solicitado #{$discount->id}");

            return $discount;
        });

        return $discount;
    }

    public function approveManualDiscount(PosManualDiscount $discount, int $approvedBy): void
    {
        $discount->update(['status' => 'approved', 'approved_by' => $approvedBy, 'approved_at' => now()]);
        $this->recalculateCart($discount->cart);
        AuditLogger::record('discount_approved', 'pos_discounts', "Descuento POS aprobado #{$discount->id}");
    }

    public function rejectManualDiscount(PosManualDiscount $discount, int $approvedBy): void
    {
        $discount->update(['status' => 'rejected', 'approved_by' => $approvedBy, 'approved_at' => now()]);
        $this->recalculateCart($discount->cart);
        AuditLogger::record('discount_rejected', 'pos_discounts', "Descuento POS rechazado #{$discount->id}");
    }

    public function addPayment(PosCart $cart, int $paymentMethodId, float $amount, ?string $reference = null): PosCartPayment
    {
        if ($amount <= 0) throw ValidationException::withMessages(['amount' => 'El monto debe ser mayor a cero.']);
        $method = PosPaymentMethod::query()->whereKey($paymentMethodId)->where('is_active', true)->firstOrFail();
        if ($method->requires_reference && blank($reference)) {
            throw ValidationException::withMessages(['reference' => 'La referencia es obligatoria para este medio de pago.']);
        }
        $payment = $cart->payments()->create(['payment_method_id' => $method->id, 'amount' => $amount, 'reference' => $reference]);
        AuditLogger::record('payment_added', 'pos_payments', "Pago POS agregado: {$method->name}");

        return $payment;
    }

    public function removePayment(PosCartPayment $payment): void
    {
        $payment->delete();
        AuditLogger::record('payment_removed', 'pos_payments', 'Pago POS eliminado');
    }

    public function recalculateCart(PosCart $cart): PosCart
    {
        $cart = $cart->fresh($this->cartRelations());
        $subtotalRegular = (float) $cart->items->sum('line_subtotal');
        $subtotal = (float) $cart->items->sum('line_total');
        $itemDiscounts = (float) $cart->items->sum('line_discount');
        $approvedManual = (float) $cart->discounts->where('status', 'approved')->sum('discount_amount');
        $couponDiscount = (float) $cart->coupons->sum('discount_amount');
        $tax = round(max(0, $subtotal - $approvedManual - $couponDiscount) * self::TAX_RATE, 2);
        $grand = max(0, round($subtotal - $approvedManual - $couponDiscount + $tax, 2));

        $cart->update([
            'subtotal_regular' => $subtotalRegular,
            'subtotal' => $subtotal,
            'item_discount_total' => $itemDiscounts,
            'coupon_discount_total' => $couponDiscount,
            'manual_discount_total' => $approvedManual,
            'tax_total' => $tax,
            'grand_total' => $grand,
        ]);

        return $cart->refresh();
    }

    public function validateCartBeforeSale(PosCart $cart): void
    {
        $cart = $this->recalculateCart($cart)->load($this->cartRelations());
        $this->assertCartActive($cart);
        if (Setting::current()->cash_require_open_session) {
            $this->cashRegisterService->requireOpenSession((int) $cart->pos_terminal_id);
        }
        if ($cart->items->isEmpty()) throw ValidationException::withMessages(['cart' => 'No se puede vender un carrito vacío.']);
        if ((float) $cart->grand_total < 0) throw ValidationException::withMessages(['cart' => 'El total no puede ser negativo.']);
        $paid = (float) $cart->payments->sum('amount');
        if (round($paid, 2) !== round((float) $cart->grand_total, 2)) {
            throw ValidationException::withMessages(['payments' => 'La suma de pagos debe ser igual al total de la venta.']);
        }
    }

    public function confirmSale(PosCart $cart): Order
    {
        return DB::transaction(function () use ($cart) {
            $cart = PosCart::query()->whereKey($cart->id)->lockForUpdate()->with($this->cartRelations())->firstOrFail();
            $this->validateCartBeforeSale($cart);
            $cashSession = Setting::current()->cash_require_open_session
                ? $this->cashRegisterService->requireOpenSession((int) $cart->pos_terminal_id)
                : $this->cashRegisterService->getOpenSession((int) $cart->pos_terminal_id);
            $customer = $cart->customer;
            $name = $customer?->display_name ?? 'Cliente mostrador';

            $order = Order::query()->create([
                'order_number' => $this->orderService->generateOrderNumber(),
                'user_id' => $customer?->user_id,
                'customer_profile_id' => $customer?->id,
                'order_channel' => 'pos',
                'pos_terminal_id' => $cart->pos_terminal_id,
                'cash_register_session_id' => $cashSession?->id,
                'sold_by' => $cart->user_id,
                'source_reference' => 'POS-CART-'.$cart->id,
                'order_status' => 'confirmed',
                'payment_status' => 'paid',
                'fulfillment_status' => 'ready',
                'subtotal_regular' => $cart->subtotal_regular,
                'subtotal' => $cart->subtotal,
                'item_discount_total' => $cart->item_discount_total,
                'coupon_discount_total' => (float) $cart->coupon_discount_total + (float) $cart->manual_discount_total,
                'shipping_total' => 0,
                'tax_total' => $cart->tax_total,
                'grand_total' => $cart->grand_total,
                'currency' => $cart->currency,
                'customer_email' => $customer?->email ?? 'cliente-pos@local',
                'customer_phone' => $customer?->phone ?? '-',
                'customer_name' => $name,
                'customer_rut' => $customer?->rut,
                'confirmed_at' => now(),
                'paid_at' => now(),
            ]);

            foreach ($cart->items as $cartItem) {
                $orderItem = $order->items()->create([
                    'item_type' => $cartItem->item_type,
                    'product_id' => $cartItem->product_id,
                    'product_variant_id' => $cartItem->product_variant_id,
                    'product_pack_id' => $cartItem->product_pack_id,
                    'product_name' => $cartItem->item_type === 'pack' ? $cartItem->pack->name : $cartItem->product->name,
                    'variant_name' => $cartItem->variant?->name,
                    'sku' => $cartItem->item_type === 'pack' ? $cartItem->pack?->sku : ($cartItem->variant?->sku ?? $cartItem->product?->sku),
                    'quantity' => $cartItem->quantity,
                    'regular_unit_price' => $cartItem->regular_unit_price,
                    'final_unit_price' => $cartItem->final_unit_price,
                    'line_subtotal' => $cartItem->line_subtotal,
                    'line_discount' => $cartItem->line_discount + $cartItem->manual_discount_amount,
                    'line_tax' => $cartItem->line_tax,
                    'line_total' => $cartItem->line_total,
                    'applied_rules' => $cartItem->applied_rules,
                    'metadata' => ['pos_cart_item_id' => $cartItem->id],
                ]);

                if ($cartItem->item_type === 'pack') {
                    foreach ($cartItem->pack->items as $component) {
                        $orderItem->packComponents()->create([
                            'product_id' => $component->product_id,
                            'product_variant_id' => $component->product_variant_id,
                            'product_name' => $component->product->name,
                            'variant_name' => $component->variant?->name,
                            'sku' => $component->variant?->sku ?? $component->product?->sku,
                            'quantity_per_pack' => $component->quantity,
                            'total_quantity' => $component->quantity * $cartItem->quantity,
                        ]);
                    }
                }
            }

            foreach ($cart->payments as $payment) {
                $orderPayment = $order->payments()->create([
                    'payment_method' => $payment->method->code,
                    'payment_label' => $payment->method->name,
                    'payment_status' => 'paid',
                    'amount' => $payment->amount,
                    'transaction_id' => $payment->reference,
                    'paid_at' => now(),
                    'metadata' => ['pos_payment_id' => $payment->id],
                ]);
                if ($cashSession) {
                    $this->cashRegisterService->registerSalePayment($order, $orderPayment);
                }
            }

            $order->shipment()->create([
                'service_name' => 'Venta presencial',
                'shipping_type' => 'pickup',
                'shipping_status' => 'delivered',
                'shipping_cost' => 0,
            ]);

            $fulfillment = $order->fulfillment()->create(['warehouse_id' => $cart->terminal->warehouse_id, 'status' => 'ready', 'assigned_to' => $cart->user_id, 'started_at' => now(), 'completed_at' => now()]);
            foreach ($order->items as $item) {
                if ($item->item_type === 'pack') {
                    foreach ($item->packComponents as $component) {
                        $fulfillment->items()->create(['order_item_id' => $item->id, 'product_id' => $component->product_id, 'product_variant_id' => $component->product_variant_id, 'required_quantity' => $component->total_quantity, 'picked_quantity' => $component->total_quantity, 'packed_quantity' => $component->total_quantity, 'status' => 'packed']);
                    }
                } else {
                    $fulfillment->items()->create(['order_item_id' => $item->id, 'product_id' => $item->product_id, 'product_variant_id' => $item->product_variant_id, 'required_quantity' => $item->quantity, 'picked_quantity' => $item->quantity, 'packed_quantity' => $item->quantity, 'status' => 'packed']);
                }
            }

            $reservations = StockReservation::query()
                ->where('reference_type', PosCartItem::class)
                ->whereIn('reference_id', $cart->items->pluck('id'))
                ->where('status', 'active')
                ->get();
            foreach ($reservations as $reservation) {
                $this->inventoryService->consumeReservation($reservation);
            }

            $order->histories()->create(['status_type' => 'order', 'new_status' => 'confirmed', 'notes' => 'Venta confirmada desde POS.', 'user_id' => $cart->user_id]);
            $order->histories()->create(['status_type' => 'payment', 'new_status' => 'paid', 'notes' => 'Pago registrado en POS.', 'user_id' => $cart->user_id]);
            $cart->update(['status' => 'converted']);
            AuditLogger::record('sale_confirmed', 'pos_sales', "Venta POS confirmada {$order->order_number}");

            return $order->load(['items.packComponents', 'payments', 'posTerminal', 'seller']);
        });
    }

    public function generateReceipt(Order $order): Order
    {
        AuditLogger::record('receipt_generated', 'pos_sales', "Comprobante generado {$order->order_number}");
        return $order->load(['items', 'payments', 'posTerminal', 'seller']);
    }

    public function createQuoteFromCart(PosCart $cart): PosQuote
    {
        $cart = $this->recalculateCart($cart)->load($this->cartRelations());
        if ($cart->items->isEmpty()) throw ValidationException::withMessages(['cart' => 'No se puede cotizar un carrito vacío.']);
        $quote = $cart->terminal->quotes()->create([
            'quote_number' => $this->number('COT'),
            'user_id' => $cart->user_id,
            'customer_profile_id' => $cart->customer_profile_id,
            'subtotal' => $cart->subtotal,
            'discount_total' => (float) $cart->item_discount_total + (float) $cart->coupon_discount_total + (float) $cart->manual_discount_total,
            'tax_total' => $cart->tax_total,
            'grand_total' => $cart->grand_total,
            'expires_at' => now()->addDays(7),
            'notes' => $cart->notes,
        ]);
        foreach ($cart->items as $item) {
            $quote->items()->create($item->only(['item_type', 'product_id', 'product_variant_id', 'product_pack_id', 'quantity', 'regular_unit_price', 'final_unit_price', 'line_total', 'applied_rules']));
        }
        AuditLogger::record('quote_created', 'pos_quotes', "Cotización POS creada {$quote->quote_number}");
        return $quote;
    }

    public function convertQuoteToCart(PosQuote $quote): PosCart
    {
        if ($quote->expires_at && $quote->expires_at->isPast()) throw ValidationException::withMessages(['quote' => 'La cotización está vencida.']);
        $cart = $this->createCart($quote->pos_terminal_id, Auth::id() ?? $quote->user_id);
        $cart->update(['customer_profile_id' => $quote->customer_profile_id, 'notes' => $quote->notes]);
        foreach ($quote->items as $item) {
            $item->item_type === 'pack'
                ? $this->addPack($cart, (int) $item->product_pack_id, (int) $item->quantity)
                : $this->addProductById($cart, (int) $item->product_id, $item->product_variant_id, (int) $item->quantity);
        }
        $quote->update(['status' => 'converted']);
        AuditLogger::record('quote_converted', 'pos_quotes', "Cotización POS convertida {$quote->quote_number}");
        return $cart;
    }

    public function createReservationFromCart(PosCart $cart): PosReservation
    {
        $cart = $cart->load($this->cartRelations());
        if ($cart->items->isEmpty()) throw ValidationException::withMessages(['cart' => 'No se puede reservar un carrito vacío.']);
        $reservation = $cart->terminal->reservations()->create([
            'reservation_number' => $this->number('RES'),
            'user_id' => $cart->user_id,
            'customer_profile_id' => $cart->customer_profile_id,
            'expires_at' => now()->addMinutes($this->reservationMinutes()),
            'notes' => $cart->notes,
        ]);
        foreach ($cart->items as $item) {
            if ($item->item_type === 'pack') {
                foreach ($item->pack->items as $component) {
                    $stockReservation = StockReservation::query()
                        ->where('reference_type', PosCartItem::class)
                        ->where('reference_id', $item->id)
                        ->where('product_id', $component->product_id)
                        ->where('product_variant_id', $component->product_variant_id)
                        ->where('status', 'active')
                        ->first();
                    if ($stockReservation) {
                        $reservation->items()->create([
                            'product_id' => $component->product_id,
                            'product_variant_id' => $component->product_variant_id,
                            'quantity' => $component->quantity * $item->quantity,
                            'stock_reservation_id' => $stockReservation->id,
                        ]);
                    }
                }
            } else {
                $reservation->items()->create([
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'stock_reservation_id' => $item->stock_reservation_id,
                ]);
            }
        }
        AuditLogger::record('reservation_created', 'pos_reservations', "Reserva POS creada {$reservation->reservation_number}");
        return $reservation;
    }

    public function convertReservationToSale(PosReservation $reservation): Order
    {
        if ($reservation->status !== 'active') throw ValidationException::withMessages(['reservation' => 'La reserva no está activa.']);
        if ($reservation->expires_at && $reservation->expires_at->isPast()) throw ValidationException::withMessages(['reservation' => 'La reserva está vencida.']);
        $cart = $this->createCart($reservation->pos_terminal_id, Auth::id() ?? $reservation->user_id);
        $cart->update(['customer_profile_id' => $reservation->customer_profile_id]);
        foreach ($reservation->items as $item) {
            $this->addProductById($cart, $item->product_id, $item->product_variant_id, $item->quantity);
        }
        $cart = $this->recalculateCart($cart);
        $cash = PosPaymentMethod::query()->where('code', 'cash')->where('is_active', true)->first();
        if ($cash && (float) $cart->grand_total > 0) {
            $this->addPayment($cart, $cash->id, (float) $cart->grand_total);
        }
        $reservation->update(['status' => 'consumed']);
        AuditLogger::record('reservation_converted', 'pos_reservations', "Reserva POS convertida {$reservation->reservation_number}");
        return $this->confirmSale($cart);
    }

    public function searchProducts(PosTerminal $terminal, string $term): Collection
    {
        $term = trim($term);
        if ($term === '') return collect();

        $products = Product::query()
            ->with(['brand', 'category', 'primaryImage', 'variants'])
            ->where('is_active', true)
            ->where(fn ($query) => $query
                ->where('name', 'like', "%{$term}%")
                ->orWhere('sku', 'like', "%{$term}%")
                ->orWhere('barcode', 'like', "%{$term}%")
                ->orWhereHas('brand', fn ($q) => $q->where('name', 'like', "%{$term}%"))
                ->orWhereHas('category', fn ($q) => $q->where('name', 'like', "%{$term}%"))
                ->orWhereHas('variants', fn ($q) => $q->where('sku', 'like', "%{$term}%")->orWhere('barcode', 'like', "%{$term}%")))
            ->limit(15)
            ->get();

        $rows = $products->flatMap(function (Product $product) use ($terminal) {
            if ($product->product_type === 'variable') {
                return $product->variants->where('is_active', true)->map(fn ($variant) => $this->searchRow($terminal, $product, $variant));
            }
            return collect([$this->searchRow($terminal, $product)]);
        });

        $packs = ProductPack::query()
            ->where('is_active', true)
            ->where('is_visible', true)
            ->where(fn ($query) => $query->where('name', 'like', "%{$term}%")->orWhere('sku', 'like', "%{$term}%")->orWhere('barcode', 'like', "%{$term}%"))
            ->limit(10)
            ->get()
            ->map(fn (ProductPack $pack) => [
                'item_type' => 'pack',
                'pack_id' => $pack->id,
                'product_id' => null,
                'variant_id' => null,
                'name' => $pack->name,
                'variant_name' => 'Pack',
                'sku' => $pack->sku,
                'barcode' => $pack->barcode,
                'brand' => null,
                'category' => null,
                'price' => $this->pricingService->calculatePackPrice($pack),
                'stock' => $this->pricingService->calculatePackAvailableStock($pack, $terminal->warehouse_id),
                'has_sale' => true,
                'image' => $pack->image_path,
            ]);

        return $rows->merge($packs)->values();
    }

    public function getCartSummary(PosCart $cart): array
    {
        $cart = $cart->fresh($this->cartRelations());
        return [
            'subtotalRegular' => (float) $cart->subtotal_regular,
            'subtotal' => (float) $cart->subtotal,
            'itemDiscounts' => (float) $cart->item_discount_total,
            'couponDiscount' => (float) $cart->coupon_discount_total,
            'manualDiscount' => (float) $cart->manual_discount_total,
            'taxTotal' => (float) $cart->tax_total,
            'grandTotal' => (float) $cart->grand_total,
            'quantity' => (int) $cart->items->sum('quantity'),
            'paid' => (float) $cart->payments->sum('amount'),
        ];
    }

    public function expireReservations(): int
    {
        $count = 0;
        PosReservation::query()->where('status', 'active')->whereNotNull('expires_at')->where('expires_at', '<', now())->with('items.stockReservation')->chunkById(50, function ($reservations) use (&$count) {
            foreach ($reservations as $reservation) {
                DB::transaction(function () use ($reservation, &$count): void {
                    foreach ($reservation->items as $item) {
                        if ($item->stockReservation?->status === 'active') {
                            $this->inventoryService->releaseReservation($item->stockReservation);
                        }
                    }
                    $reservation->update(['status' => 'expired']);
                    $count++;
                    AuditLogger::record('reservation_released', 'pos_reservations', "Reserva POS vencida {$reservation->reservation_number}");
                });
            }
        });

        return $count;
    }

    private function searchRow(PosTerminal $terminal, Product $product, ?ProductVariant $variant = null): array
    {
        $price = $this->pricingService->getBestPrice($product, $variant, 1);
        $stock = $this->inventoryService->getAvailableStock($terminal->warehouse_id, $product->id, $variant?->id);
        return [
            'item_type' => 'product',
            'product_id' => $product->id,
            'variant_id' => $variant?->id,
            'name' => $product->name,
            'variant_name' => $variant?->name,
            'sku' => $variant?->sku ?? $product->sku,
            'barcode' => $variant?->barcode ?? $product->barcode,
            'brand' => $product->brand?->name,
            'category' => $product->category?->name,
            'price' => $price['final_price'],
            'stock' => $stock,
            'has_sale' => $this->pricingService->hasActiveSale($product, $variant),
            'image' => $variant?->image_path ?? $product->primaryImage->first()?->image_path,
        ];
    }

    private function releaseItemReservations(PosCartItem $item): void
    {
        $reservations = StockReservation::query()
            ->where('reference_type', PosCartItem::class)
            ->where('reference_id', $item->id)
            ->where('status', 'active')
            ->get();
        foreach ($reservations as $reservation) {
            $this->inventoryService->releaseReservation($reservation);
        }
    }

    private function cartRelations(): array
    {
        return ['terminal', 'user', 'customer', 'items.product', 'items.variant', 'items.pack.items.product', 'items.pack.items.variant', 'coupons.coupon', 'payments.method', 'discounts'];
    }

    private function assertCartActive(PosCart $cart): void
    {
        if ($cart->status !== 'active') throw ValidationException::withMessages(['cart' => 'El carrito POS no está activo.']);
        $this->assertTerminalActive((int) $cart->pos_terminal_id);
    }

    private function assertTerminalActive(int $terminalId): void
    {
        if (! PosTerminal::query()->whereKey($terminalId)->where('is_active', true)->exists()) {
            AuditLogger::record('unauthorized_sale_attempt', 'pos', 'Intento de operar con terminal POS inactivo.');
            throw ValidationException::withMessages(['terminal' => 'Terminal POS inactivo o inexistente.']);
        }
    }

    private function assertQuantity(int $quantity): void
    {
        if ($quantity < 1) throw ValidationException::withMessages(['quantity' => 'La cantidad debe ser mayor a cero.']);
    }

    private function reservationMinutes(): int
    {
        return max(1, (int) (Setting::current()->pos_reservation_minutes ?? 60));
    }

    private function discountBase(PosCart $cart, ?int $itemId): float
    {
        if ($itemId) {
            return (float) $cart->items()->whereKey($itemId)->value('line_total');
        }
        return (float) $cart->subtotal;
    }

    private function calculateManualDiscount(float $base, string $type, float $value): float
    {
        return $type === 'percentage' ? round($base * ($value / 100), 2) : round($value, 2);
    }

    private function number(string $prefix): string
    {
        do {
            $number = $prefix.'-'.now()->format('Ymd').'-'.random_int(1000, 9999);
            $exists = $prefix === 'COT'
                ? PosQuote::query()->where('quote_number', $number)->exists()
                : PosReservation::query()->where('reservation_number', $number)->exists();
        } while ($exists);
        return $number;
    }
}
