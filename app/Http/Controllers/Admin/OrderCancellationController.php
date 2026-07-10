<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderCancellationController extends Controller
{
    public function create(Order $order): View
    {
        return view('admin.orders.cancel', compact('order'));
    }

    public function store(Request $request, Order $order, OrderService $service): RedirectResponse
    {
        $data = $request->validate(['reason' => ['required', 'string', 'max:255'], 'notes' => ['nullable', 'string'], 'restore_stock' => ['nullable', 'boolean']]);
        $service->cancelOrder($order, $data['reason'], $request->boolean('restore_stock', true), $data['notes'] ?? null);
        return redirect()->route('admin.orders.show', $order)->with('success', 'Pedido cancelado.');
    }
}
