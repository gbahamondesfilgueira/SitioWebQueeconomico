<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function confirm(Request $request, CartService $cartService, OrderService $orderService): RedirectResponse
    {
        $cart = $cartService->getOrCreateCart($request->user(), $request->session()->getId());
        $order = $orderService->createOrderFromCart($cart, $request->user());
        session(['last_order_number' => $order->order_number]);

        return redirect()->route('store.orders.confirmed', $order->order_number);
    }

    public function confirmed(Request $request, string $order_number): View
    {
        abort_unless(session('last_order_number') === $order_number || $request->user()?->orders()->where('order_number', $order_number)->exists(), 403);
        $order = Order::query()->with(['items.packComponents', 'addresses', 'payments', 'shipment'])->where('order_number', $order_number)->firstOrFail();

        return view('store.orders.confirmed', ['order' => $order, 'seo' => ['title' => 'Pedido confirmado']]);
    }

    public function accountIndex(Request $request): View
    {
        $orders = Order::query()->where('user_id', $request->user()->id)->latest()->paginate(10);
        return view('store.account.orders.index', ['orders' => $orders, 'seo' => ['title' => 'Mis pedidos']]);
    }

    public function accountShow(Request $request, string $order_number): View
    {
        $order = Order::query()->with(['items.packComponents', 'addresses', 'payments', 'shipment', 'histories'])->where('user_id', $request->user()->id)->where('order_number', $order_number)->firstOrFail();
        return view('store.account.orders.show', ['order' => $order, 'seo' => ['title' => 'Pedido '.$order->order_number]]);
    }
}
