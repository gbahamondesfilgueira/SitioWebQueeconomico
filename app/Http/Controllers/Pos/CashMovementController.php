<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pos\Concerns\ResolvesPosTerminal;
use App\Models\PosPaymentMethod;
use App\Services\CashRegisterService;
use Illuminate\Http\Request;

class CashMovementController extends Controller
{
    use ResolvesPosTerminal;

    public function movements(Request $request, CashRegisterService $cashRegisterService)
    {
        $session = $cashRegisterService->requireOpenSession($this->terminal($request)->id);
        return view('pos.cash.movements', ['session' => $session->load(['movements.paymentMethod', 'movements.user'])]);
    }

    public function income(Request $request, CashRegisterService $cashRegisterService)
    {
        return view('pos.cash.income', ['session' => $cashRegisterService->requireOpenSession($this->terminal($request)->id), 'methods' => PosPaymentMethod::query()->where('is_active', true)->get()]);
    }

    public function storeIncome(Request $request, CashRegisterService $cashRegisterService)
    {
        $data = $request->validate(['amount' => ['required', 'numeric', 'min:1'], 'description' => ['required', 'string', 'max:190'], 'payment_method_id' => ['nullable', 'exists:pos_payment_methods,id']]);
        $cashRegisterService->registerIncome($cashRegisterService->requireOpenSession($this->terminal($request)->id), (float) $data['amount'], $data['description'], $data['payment_method_id'] ?? null);
        return redirect()->route('pos.cash.current')->with('success', 'Ingreso registrado.');
    }

    public function expense(Request $request, CashRegisterService $cashRegisterService)
    {
        return view('pos.cash.expense', ['session' => $cashRegisterService->requireOpenSession($this->terminal($request)->id), 'methods' => PosPaymentMethod::query()->where('is_active', true)->get()]);
    }

    public function storeExpense(Request $request, CashRegisterService $cashRegisterService)
    {
        $data = $request->validate(['amount' => ['required', 'numeric', 'min:1'], 'description' => ['required', 'string', 'max:190'], 'payment_method_id' => ['nullable', 'exists:pos_payment_methods,id'], 'movement_kind' => ['required', 'in:expense,withdrawal']]);
        $session = $cashRegisterService->requireOpenSession($this->terminal($request)->id);
        $data['movement_kind'] === 'withdrawal'
            ? $cashRegisterService->registerWithdrawal($session, (float) $data['amount'], $data['description'])
            : $cashRegisterService->registerExpense($session, (float) $data['amount'], $data['description'], $data['payment_method_id'] ?? null);
        return redirect()->route('pos.cash.current')->with('success', 'Egreso registrado.');
    }
}
