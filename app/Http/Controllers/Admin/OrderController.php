<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->when($request->filled('order_status'), fn ($q) => $q->where('order_status', $request->order_status))
            ->when($request->filled('payment_status'), fn ($q) => $q->where('payment_status', $request->payment_status))
            ->when($request->filled('customer'), fn ($q) => $q->where('customer_name', 'like', "%{$request->customer}%")->orWhere('customer_email', 'like', "%{$request->customer}%"))
            ->when($request->filled('order_number'), fn ($q) => $q->where('order_number', 'like', "%{$request->order_number}%"))
            ->when($request->filled('date_from'), fn ($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn ($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', ['order' => $order->load(['items.packComponents', 'addresses', 'payments', 'shipment', 'histories.user', 'fulfillment.items', 'cancellations', 'returns'])]);
    }

    public function status(Request $request, Order $order, OrderService $service): RedirectResponse
    {
        $data = $request->validate(['order_status' => ['required', 'string'], 'notes' => ['nullable', 'string']]);
        $service->updateOrderStatus($order, $data['order_status'], $data['notes'] ?? null);
        return back()->with('success', 'Estado de pedido actualizado.');
    }

    public function paymentStatus(Request $request, Order $order, OrderService $service): RedirectResponse
    {
        $data = $request->validate(['payment_status' => ['required', 'string'], 'notes' => ['nullable', 'string']]);
        $service->updatePaymentStatus($order, $data['payment_status'], $data['notes'] ?? null);
        return back()->with('success', 'Estado de pago actualizado.');
    }
}
