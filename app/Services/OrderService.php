<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\CartSession;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\StockReservation;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(
        private CartService $cartService,
        private InventoryService $inventoryService,
    ) {}

    public function createOrderFromCart(CartSession $cart, ?User $user = null): Order
    {
        return DB::transaction(function () use ($cart, $user) {
            $cart = CartSession::query()->whereKey($cart->id)->lockForUpdate()->with(['items.product', 'items.variant', 'items.pack.items.product', 'items.pack.items.variant', 'addresses', 'paymentMethod', 'shippingMethod', 'coupons'])->firstOrFail();
            if ($cart->status !== 'active') {
                throw ValidationException::withMessages(['cart' => 'El carrito ya fue confirmado o no está activo.']);
            }
            if ($cart->items->isEmpty()) {
                throw ValidationException::withMessages(['cart' => 'El carrito está vacío.']);
            }
            if (! $cart->addresses->where('address_type', 'shipping')->first()) {
                throw ValidationException::withMessages(['shipping' => 'Falta dirección de despacho.']);
            }
            if (! $cart->addresses->where('address_type', 'billing')->first()) {
                throw ValidationException::withMessages(['billing' => 'Falta dirección de facturación.']);
            }
            if (! $cart->paymentMethod) {
                throw ValidationException::withMessages(['payment' => 'Falta método de pago.']);
            }
            if (! $cart->shippingMethod) {
                throw ValidationException::withMessages(['shipping_method' => 'Falta método de envío.']);
            }

            $this->cartService->recalculateCart($cart);
            $summary = $this->cartService->getCartSummary($cart);
            $customer = session('checkout.customer', []);
            $profile = $cart->user?->customerProfile;
            $name = trim(($profile?->first_name ?? $customer['first_name'] ?? 'Cliente').' '.($profile?->last_name ?? $customer['last_name'] ?? 'Invitado'));

            $order = Order::query()->create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $cart->user_id ?? $user?->id,
                'customer_profile_id' => $profile?->id,
                'cart_session_id' => $cart->id,
                'order_status' => 'confirmed',
                'payment_status' => 'pending',
                'fulfillment_status' => 'pending',
                'subtotal_regular' => $summary['subtotalRegular'],
                'subtotal' => $summary['subtotal'],
                'item_discount_total' => $summary['itemDiscounts'],
                'coupon_discount_total' => $summary['couponDiscount'],
                'shipping_total' => $summary['shippingEstimate'],
                'tax_total' => $summary['taxTotal'],
                'grand_total' => $summary['grandTotal'],
                'customer_email' => $profile?->email ?? $customer['email'] ?? $cart->user?->email ?? 'sin-email@local',
                'customer_phone' => $profile?->phone ?? $customer['phone'] ?? '-',
                'customer_name' => $name,
                'customer_rut' => $profile?->rut ?? $customer['rut'] ?? null,
                'confirmed_at' => now(),
            ]);

            $this->createOrderItems($order, $cart);
            $this->createOrderAddresses($order, $cart);
            $this->createOrderPayment($order, $cart);
            $this->createOrderShipment($order, $cart);
            $reservations = StockReservation::query()
                ->where('reference_type', CartItem::class)
                ->whereIn('reference_id', $cart->items->pluck('id'))
                ->where('status', 'active')
                ->get();
            $this->createFulfillments($order, $reservations);
            $this->consumeStockReservations($order, $reservations);
            $this->recordStatus($order, 'order', null, 'confirmed', 'Pedido confirmado desde checkout.');
            $cart->update(['status' => 'converted']);

            AuditLogger::record('created', 'orders', "Pedido creado {$order->order_number}");

            return $order->load(['items.packComponents', 'addresses', 'payments', 'shipment']);
        });
    }

    public function generateOrderNumber(): string
    {
        do {
            $number = 'QE-'.now()->format('Ymd').'-'.str_pad((string) random_int(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (Order::query()->where('order_number', $number)->exists());

        return $number;
    }

    public function recalculateOrderTotals(CartSession $cart): array
    {
        return $this->cartService->getCartSummary($cart);
    }

    public function createOrderItems(Order $order, CartSession $cart): void
    {
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
                'regular_unit_price' => $cartItem->regular_price ?? 0,
                'final_unit_price' => $cartItem->final_unit_price,
                'line_subtotal' => $cartItem->line_subtotal,
                'line_discount' => $cartItem->line_discount,
                'line_tax' => round((float) $cartItem->line_total * CartService::TAX_RATE, 2),
                'line_total' => $cartItem->line_total,
                'applied_rules' => $cartItem->applied_rules,
                'metadata' => ['cart_item_id' => $cartItem->id],
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
    }

    public function createOrderAddresses(Order $order, CartSession $cart): void
    {
        foreach ($cart->addresses as $address) {
            $order->addresses()->create($address->only(['address_type', 'contact_name', 'phone', 'email', 'country', 'region', 'commune', 'city', 'street', 'number', 'apartment', 'postal_code', 'reference']));
        }
    }

    public function createOrderPayment(Order $order, CartSession $cart): void
    {
        $payment = $cart->paymentMethod;
        $order->payments()->create([
            'payment_method' => $payment->payment_method,
            'payment_label' => $payment->payment_label,
            'payment_status' => 'pending',
            'amount' => $order->grand_total,
            'metadata' => $payment->metadata,
        ]);
    }

    public function createOrderShipment(Order $order, CartSession $cart): void
    {
        $shipping = $cart->shippingMethod;
        $order->shipment()->create([
            'shipping_carrier_id' => $shipping->quote?->shipping_carrier_id,
            'shipping_service_id' => $shipping->quote?->shipping_service_id,
            'shipping_quote_id' => $shipping->shipping_quote_id,
            'carrier_name' => $shipping->carrier_name,
            'service_name' => $shipping->service_name,
            'shipping_type' => $shipping->shipping_type,
            'shipping_status' => 'pending',
            'shipping_cost' => $shipping->estimated_price,
            'estimated_days' => $shipping->estimated_days,
            'estimated_days_min' => $shipping->estimated_days_min,
            'estimated_days_max' => $shipping->estimated_days_max,
            'metadata' => $shipping->metadata,
        ]);
    }

    public function consumeStockReservations(Order $order, Collection $reservations): void
    {
        foreach ($reservations as $reservation) {
            $this->inventoryService->consumeReservation($reservation);
        }
        AuditLogger::record('consumed', 'orders', "Stock consumido para pedido {$order->order_number}");
    }

    public function updateOrderStatus(Order $order, string $newStatus, ?string $notes = null): Order
    {
        $allowed = ['pending', 'confirmed', 'paid', 'preparing', 'ready_to_ship', 'shipped', 'delivered', 'completed', 'cancelled', 'refunded'];
        if (! in_array($newStatus, $allowed, true)) {
            throw ValidationException::withMessages(['status' => 'Estado de pedido inválido.']);
        }
        $old = $order->order_status;
        $order->update(['order_status' => $newStatus, 'completed_at' => $newStatus === 'completed' ? now() : $order->completed_at]);
        $this->recordStatus($order, 'order', $old, $newStatus, $notes);
        AuditLogger::record('status_updated', 'orders', "Pedido {$order->order_number}: {$old} -> {$newStatus}");

        return $order->refresh();
    }

    public function updatePaymentStatus(Order $order, string $newStatus, ?string $notes = null): Order
    {
        $allowed = ['pending', 'paid', 'partially_paid', 'failed', 'refunded'];
        if (! in_array($newStatus, $allowed, true)) {
            throw ValidationException::withMessages(['status' => 'Estado de pago inválido.']);
        }
        $old = $order->payment_status;
        $order->update(['payment_status' => $newStatus, 'paid_at' => $newStatus === 'paid' ? now() : $order->paid_at]);
        $order->payments()->latest()->first()?->update(['payment_status' => $newStatus === 'partially_paid' ? 'pending' : $newStatus, 'paid_at' => $newStatus === 'paid' ? now() : null]);
        $this->recordStatus($order, 'payment', $old, $newStatus, $notes);
        AuditLogger::record('payment_updated', 'order_payments', "Pago pedido {$order->order_number}: {$newStatus}");

        return $order->refresh();
    }

    public function updateFulfillmentStatus(Order $order, string $newStatus, ?string $notes = null): Order
    {
        $allowed = ['pending', 'picking', 'packed', 'ready', 'shipped', 'delivered', 'cancelled'];
        if (! in_array($newStatus, $allowed, true)) {
            throw ValidationException::withMessages(['status' => 'Estado de preparación inválido.']);
        }
        $old = $order->fulfillment_status;
        $order->update(['fulfillment_status' => $newStatus]);
        $this->recordStatus($order, 'fulfillment', $old, $newStatus, $notes);
        AuditLogger::record('fulfillment_updated', 'order_fulfillments', "Preparación pedido {$order->order_number}: {$newStatus}");

        return $order->refresh();
    }

    public function cancelOrder(Order $order, string $reason, bool $restoreStock = true, ?string $notes = null): Order
    {
        return DB::transaction(function () use ($order, $reason, $restoreStock, $notes) {
            if (in_array($order->order_status, ['completed', 'cancelled'], true)) {
                throw ValidationException::withMessages(['order' => 'No se puede cancelar este pedido.']);
            }
            if ($order->cancellations()->exists()) {
                throw ValidationException::withMessages(['order' => 'El pedido ya tiene cancelación registrada.']);
            }
            $order->cancellations()->create(['reason' => $reason, 'notes' => $notes, 'cancelled_by' => Auth::id() ?? 1, 'cancelled_at' => now(), 'restore_stock' => $restoreStock]);
            if ($restoreStock) {
                $this->restoreOrderStock($order);
            }
            $this->updateOrderStatus($order, 'cancelled', $reason);
            $this->updateFulfillmentStatus($order, 'cancelled', $reason);
            $order->update(['cancelled_at' => now()]);
            AuditLogger::record('cancelled', 'orders', "Pedido cancelado {$order->order_number}");

            return $order->refresh();
        });
    }

    public function createReturn(Order $order, array $items, string $reason, ?string $notes = null): OrderReturn
    {
        return DB::transaction(function () use ($order, $items, $reason, $notes) {
            $return = $order->returns()->create(['return_number' => 'RET-'.now()->format('Ymd').'-'.random_int(1000, 9999), 'reason' => $reason, 'notes' => $notes, 'requested_by' => Auth::id()]);
            foreach ($items as $item) {
                $orderItem = $order->items()->whereKey($item['order_item_id'])->firstOrFail();
                if ((int) $item['quantity'] > $orderItem->quantity) {
                    throw ValidationException::withMessages(['quantity' => 'No puedes devolver más unidades que las compradas.']);
                }
                $return->items()->create(['order_item_id' => $orderItem->id, 'quantity' => $item['quantity'], 'condition' => $item['condition'] ?? 'opened', 'restock' => (bool) ($item['restock'] ?? false), 'notes' => $item['notes'] ?? null]);
            }
            AuditLogger::record('requested', 'order_returns', "Devolución solicitada {$return->return_number}");

            return $return;
        });
    }

    public function approveReturn(OrderReturn $return): OrderReturn
    {
        $return->update(['status' => 'approved', 'approved_by' => Auth::id(), 'approved_at' => now()]);
        AuditLogger::record('approved', 'order_returns', "Devolución aprobada {$return->return_number}");

        return $return;
    }

    public function receiveReturn(OrderReturn $return): OrderReturn
    {
        return DB::transaction(function () use ($return) {
            foreach ($return->items()->with('orderItem')->get() as $item) {
                if ($item->restock) {
                    $orderItem = $item->orderItem;
                    $sources = $return->order->fulfillments()
                        ->with('items')
                        ->get()
                        ->flatMap(fn ($fulfillment) => $fulfillment->items->map(fn ($fulfillmentItem) => [$fulfillment, $fulfillmentItem]))
                        ->filter(fn ($source) => $source[1]->order_item_id === $orderItem->id);

                    foreach ($sources as [$fulfillment, $fulfillmentItem]) {
                        $restockQuantity = (float) $fulfillmentItem->required_quantity * ((int) $item->quantity / max(1, (int) $orderItem->quantity));
                        $this->inventoryService->increaseStock($fulfillment->warehouse_id, $fulfillmentItem->product_id, $fulfillmentItem->product_variant_id, $fulfillmentItem->warehouse_location_id, $restockQuantity, 'return_in', 'Devolución recibida', OrderReturn::class, $return->id);
                    }
                }
            }
            $return->update(['status' => 'received', 'received_at' => now()]);
            AuditLogger::record('received', 'order_returns', "Devolución recibida {$return->return_number}");

            return $return;
        });
    }

    private function createFulfillments(Order $order, Collection $reservations): void
    {
        $order->load('items');
        $orderItems = $order->items->keyBy(fn ($item) => (int) data_get($item->metadata, 'cart_item_id'));

        foreach ($reservations->groupBy('warehouse_id') as $warehouseId => $warehouseReservations) {
            $fulfillment = $order->fulfillments()->create(['warehouse_id' => $warehouseId, 'status' => 'pending']);

            foreach ($warehouseReservations as $reservation) {
                $orderItem = $orderItems->get((int) $reservation->reference_id);
                if (! $orderItem) {
                    throw ValidationException::withMessages(['cart' => 'No fue posible vincular la reserva con el pedido.']);
                }

                $fulfillment->items()->create([
                    'order_item_id' => $orderItem->id,
                    'product_id' => $reservation->product_id,
                    'product_variant_id' => $reservation->product_variant_id,
                    'warehouse_location_id' => $reservation->warehouse_location_id,
                    'stock_reservation_id' => $reservation->id,
                    'required_quantity' => $reservation->quantity,
                ]);
            }
        }
    }

    private function restoreOrderStock(Order $order): void
    {
        foreach ($order->fulfillments()->with('items')->get() as $fulfillment) {
            foreach ($fulfillment->items as $item) {
                $this->inventoryService->increaseStock($fulfillment->warehouse_id, $item->product_id, $item->product_variant_id, $item->warehouse_location_id, (float) $item->required_quantity, 'return_in', 'Stock restaurado por cancelación', Order::class, $order->id);
            }
        }
        AuditLogger::record('restored', 'orders', "Stock restaurado pedido {$order->order_number}");
    }

    private function recordStatus(Order $order, string $type, ?string $old, string $new, ?string $notes = null): void
    {
        $order->histories()->create(['status_type' => $type, 'old_status' => $old, 'new_status' => $new, 'notes' => $notes, 'user_id' => Auth::id()]);
    }
}
