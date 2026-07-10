<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PosPaymentMethod;
use App\Models\PosRefund;
use App\Services\CashRegisterService;
use Illuminate\Http\Request;

class PosRefundController extends Controller
{
    public function create(Order $order)
    {
        abort_unless($order->order_channel === 'pos', 404);
        return view('pos.refunds.create', ['order' => $order, 'methods' => PosPaymentMethod::query()->where('is_active', true)->get()]);
    }

    public function store(Order $order, Request $request, CashRegisterService $cashRegisterService)
    {
        $data = $request->validate(['refund_amount' => ['required', 'numeric', 'min:1'], 'reason' => ['required', 'string', 'max:190'], 'refund_method' => ['required', 'string', 'max:80'], 'restore_stock' => ['nullable', 'boolean'], 'notes' => ['nullable', 'string']]);
        $refund = $cashRegisterService->refundPosSale($order, (float) $data['refund_amount'], $data['reason'], $request->boolean('restore_stock'), $data['refund_method'], $data['notes'] ?? null);
        return redirect()->route('pos.refunds.show', $refund)->with('success', 'Devolución registrada.');
    }

    public function show(PosRefund $refund)
    {
        return view('pos.refunds.show', ['refund' => $refund->load(['order', 'session', 'processor'])]);
    }
}
