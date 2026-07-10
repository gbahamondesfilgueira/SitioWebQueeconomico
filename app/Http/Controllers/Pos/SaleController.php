<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pos\Concerns\ResolvesPosTerminal;
use App\Models\PosPaymentMethod;
use App\Models\Setting;
use App\Services\CashRegisterService;
use App\Services\PosService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    use ResolvesPosTerminal;

    public function create(Request $request, PosService $posService, CashRegisterService $cashRegisterService)
    {
        $terminal = $this->terminal($request);
        if (Setting::current()->cash_require_open_session && ! $cashRegisterService->getOpenSession($terminal->id)) {
            return redirect()->route('pos.cash.open')->withErrors(['cash_register' => 'Debes abrir caja antes de vender.']);
        }
        $cart = $posService->getActiveCart($terminal->id, $request->user()->id);

        return view('pos.sales.create', [
            'terminal' => $terminal,
            'cart' => $cart->load(['items.product', 'items.variant', 'items.pack', 'payments.method', 'customer', 'coupons']),
            'paymentMethods' => PosPaymentMethod::query()->where('is_active', true)->orderBy('name')->get(),
            'summary' => $posService->getCartSummary($cart),
        ]);
    }

    public function confirm(Request $request, PosService $posService)
    {
        $terminal = $this->terminal($request);
        $cart = $posService->getActiveCart($terminal->id, $request->user()->id);
        $order = $posService->confirmSale($cart);

        return redirect()->route('pos.orders.receipt', $order)->with('success', 'Venta POS confirmada correctamente.');
    }
}
