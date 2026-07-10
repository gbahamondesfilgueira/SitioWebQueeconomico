<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pos\Concerns\ResolvesPosTerminal;
use App\Services\PosService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    use ResolvesPosTerminal;

    public function apply(Request $request, PosService $posService)
    {
        $data = $request->validate(['coupon_code' => ['required', 'string', 'max:80']]);
        $cart = $posService->getActiveCart($this->terminal($request)->id, $request->user()->id);
        $posService->applyCoupon($cart, $data['coupon_code']);
        return back()->with('success', 'Cupón aplicado.');
    }

    public function remove(Request $request, PosService $posService)
    {
        $data = $request->validate(['coupon_code' => ['required', 'string', 'max:80']]);
        $cart = $posService->getActiveCart($this->terminal($request)->id, $request->user()->id);
        $posService->removeCoupon($cart, $data['coupon_code']);
        return back()->with('success', 'Cupón eliminado.');
    }
}
