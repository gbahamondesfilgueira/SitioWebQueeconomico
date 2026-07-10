<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request, CartService $cartService): RedirectResponse
    {
        $data = $request->validate(['coupon_code' => ['required', 'string', 'max:100']]);
        $cart = $cartService->getOrCreateCart($request->user(), $request->session()->getId());
        $cartService->applyCoupon($cart, $data['coupon_code']);

        return back()->with('success', 'Cupón aplicado.');
    }

    public function remove(Request $request, CartService $cartService): RedirectResponse
    {
        $cart = $cartService->getOrCreateCart($request->user(), $request->session()->getId());
        $cartService->removeCoupon($cart);

        return back()->with('success', 'Cupón quitado.');
    }
}
