<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pos\Concerns\ResolvesPosTerminal;
use App\Models\PosPaymentMethod;
use App\Models\PosTerminal;
use App\Services\CashRegisterService;
use Illuminate\Http\Request;

class CashRegisterController extends Controller
{
    use ResolvesPosTerminal;

    public function current(Request $request, CashRegisterService $cashRegisterService)
    {
        $terminal = $this->terminal($request);
        $session = $cashRegisterService->getOpenSession($terminal->id);
        if (! $session) return redirect()->route('pos.cash.open');

        return view('pos.cash.current', [
            'terminal' => $terminal,
            'session' => $session->load(['movements.paymentMethod', 'opener']),
            'summary' => $cashRegisterService->summary($session),
        ]);
    }

    public function open(Request $request, CashRegisterService $cashRegisterService)
    {
        $terminal = $this->terminal($request);
        return view('pos.cash.open', [
            'terminal' => $terminal,
            'terminals' => PosTerminal::query()->where('is_active', true)->orderBy('name')->get(),
            'openSession' => $cashRegisterService->getOpenSession($terminal->id),
        ]);
    }

    public function storeOpen(Request $request, CashRegisterService $cashRegisterService)
    {
        $data = $request->validate([
            'terminal_id' => ['required', 'exists:pos_terminals,id'],
            'opening_amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);
        $session = $cashRegisterService->openSession((int) $data['terminal_id'], $request->user()->id, (float) $data['opening_amount'], $data['notes'] ?? null);
        session(['pos_terminal_id' => $session->pos_terminal_id]);

        return redirect()->route('pos.cash.current')->with('success', 'Caja abierta correctamente.');
    }

    public function close(Request $request, CashRegisterService $cashRegisterService)
    {
        $session = $cashRegisterService->requireOpenSession($this->terminal($request)->id);
        return view('pos.cash.close', [
            'session' => $session->load(['terminal', 'movements.paymentMethod']),
            'summary' => $cashRegisterService->summary($session),
        ]);
    }

    public function storeClose(Request $request, CashRegisterService $cashRegisterService)
    {
        $data = $request->validate(['counted_cash_amount' => ['required', 'numeric', 'min:0'], 'notes' => ['nullable', 'string']]);
        $session = $cashRegisterService->requireOpenSession($this->terminal($request)->id);
        $closed = $cashRegisterService->closeSession($session, (float) $data['counted_cash_amount'], $request->user()->id, $data['notes'] ?? null);

        return redirect()->route('pos.cash.report', ['session' => $closed->id])->with('success', 'Caja cerrada correctamente.');
    }
}
