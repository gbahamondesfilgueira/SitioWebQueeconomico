<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PosSaleCancellation;
use App\Services\CashRegisterService;
use Illuminate\Http\Request;

class PosCancellationController extends Controller
{
    public function create(Order $order)
    {
        abort_unless($order->order_channel === 'pos', 404);
        return view('pos.cancellations.create', ['order' => $order]);
    }

    public function store(Order $order, Request $request, CashRegisterService $cashRegisterService)
    {
        $data = $request->validate(['reason' => ['required', 'string', 'max:190'], 'notes' => ['nullable', 'string'], 'restore_stock' => ['nullable', 'boolean']]);
        $cancellation = $cashRegisterService->cancelPosSale($order, $data['reason'], $request->boolean('restore_stock', true), $data['notes'] ?? null);
        return redirect()->route('pos.cancellations.show', $cancellation)->with('success', 'Venta anulada.');
    }

    public function show(PosSaleCancellation $cancellation)
    {
        return view('pos.cancellations.show', ['cancellation' => $cancellation->load(['order', 'session', 'user'])]);
    }
}
