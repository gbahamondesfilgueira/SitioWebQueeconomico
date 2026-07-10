<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pos\Concerns\ResolvesPosTerminal;
use App\Models\PosCartPayment;
use App\Services\PosService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ResolvesPosTerminal;

    public function add(Request $request, PosService $posService)
    {
        $data = $request->validate(['payment_method_id' => ['required', 'integer'], 'amount' => ['required', 'numeric', 'min:1'], 'reference' => ['nullable', 'string', 'max:120']]);
        $cart = $posService->getActiveCart($this->terminal($request)->id, $request->user()->id);
        $posService->addPayment($cart, (int) $data['payment_method_id'], (float) $data['amount'], $data['reference'] ?? null);
        return back()->with('success', 'Pago agregado.');
    }

    public function remove(Request $request, PosService $posService)
    {
        $data = $request->validate(['payment_id' => ['required', 'integer']]);
        $payment = PosCartPayment::query()->whereKey($data['payment_id'])->whereHas('cart', fn ($q) => $q->where('user_id', $request->user()->id)->where('status', 'active'))->firstOrFail();
        $posService->removePayment($payment);
        return back()->with('success', 'Pago eliminado.');
    }
}
