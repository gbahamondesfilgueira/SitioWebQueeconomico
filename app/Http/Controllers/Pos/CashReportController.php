<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pos\Concerns\ResolvesPosTerminal;
use App\Models\CashRegisterSession;
use App\Services\AuditLogger;
use App\Services\CashRegisterService;
use Illuminate\Http\Request;

class CashReportController extends Controller
{
    use ResolvesPosTerminal;

    public function __invoke(Request $request, CashRegisterService $cashRegisterService)
    {
        $session = $request->integer('session')
            ? CashRegisterSession::query()->findOrFail($request->integer('session'))
            : $cashRegisterService->requireOpenSession($this->terminal($request)->id);
        AuditLogger::record('viewed', 'cash_register_sessions', "Reporte de caja consultado #{$session->id}");

        return view('pos.cash.report', [
            'session' => $session->load(['terminal', 'opener', 'closer', 'movements.paymentMethod', 'orders.payments']),
            'summary' => $cashRegisterService->summary($session),
        ]);
    }
}
