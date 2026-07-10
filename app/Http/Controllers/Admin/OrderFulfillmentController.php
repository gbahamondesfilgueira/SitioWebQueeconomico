<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderFulfillmentController extends Controller
{
    public function show(Order $order): View
    {
        return view('admin.orders.fulfillment', ['order' => $order->load('fulfillment.items.orderItem')]);
    }

    public function update(Request $request, Order $order, OrderService $service): RedirectResponse
    {
        $data = $request->validate(['fulfillment_status' => ['required', 'string'], 'notes' => ['nullable', 'string']]);
        $service->updateFulfillmentStatus($order, $data['fulfillment_status'], $data['notes'] ?? null);
        $order->fulfillment?->update(['status' => $data['fulfillment_status'] === 'picking' ? 'picking' : ($data['fulfillment_status'] === 'packed' ? 'packed' : ($data['fulfillment_status'] === 'ready' ? 'ready' : $order->fulfillment->status))]);
        return back()->with('success', 'Preparación actualizada.');
    }
}
