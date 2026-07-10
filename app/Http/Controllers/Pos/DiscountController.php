<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pos\Concerns\ResolvesPosTerminal;
use App\Models\PosManualDiscount;
use App\Services\PosService;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    use ResolvesPosTerminal;

    public function request(Request $request, PosService $posService)
    {
        $data = $request->validate([
            'pos_cart_item_id' => ['nullable', 'integer'],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => ['required', 'numeric', 'min:0.01'],
            'reason' => ['required', 'string', 'max:190'],
        ]);
        $cart = $posService->getActiveCart($this->terminal($request)->id, $request->user()->id);
        $posService->requestManualDiscount($cart, $data);
        return back()->with('success', 'Descuento registrado.');
    }

    public function approve(PosManualDiscount $discount, Request $request, PosService $posService)
    {
        abort_unless($request->user()->hasRole(['super-admin', 'administrador']), 403);
        $posService->approveManualDiscount($discount, $request->user()->id);
        return back()->with('success', 'Descuento aprobado.');
    }

    public function reject(PosManualDiscount $discount, Request $request, PosService $posService)
    {
        abort_unless($request->user()->hasRole(['super-admin', 'administrador']), 403);
        $posService->rejectManualDiscount($discount, $request->user()->id);
        return back()->with('success', 'Descuento rechazado.');
    }
}
