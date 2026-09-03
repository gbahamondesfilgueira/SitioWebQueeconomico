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
        return view('admin.orders.fulfillment', ['order' => $order->load('fulfillments.warehouse', 'fulfillments.items.orderItem', 'fulfillments.items.location')]);
    }

    public function update(Request $request, Order $order, OrderService $service): RedirectResponse
    {
        $data = $request->validate(['fulfillment_status' => ['required', 'string'], 'notes' => ['nullable', 'string']]);
        $service->updateFulfillmentStatus($order, $data['fulfillment_status'], $data['notes'] ?? null);
        $mappedStatus = in_array($data['fulfillment_status'], ['picking', 'packed', 'ready', 'cancelled'], true) ? $data['fulfillment_status'] : null;
        if ($mappedStatus) {
            $order->fulfillments()->update(['status' => $mappedStatus]);
        }

        return back()->with('success', 'Preparación actualizada.');
    }
}
