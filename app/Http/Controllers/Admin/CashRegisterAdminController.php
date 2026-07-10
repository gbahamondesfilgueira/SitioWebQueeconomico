<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashRegisterSession;
use App\Services\CashRegisterService;

class CashRegisterAdminController extends Controller
{
    public function index()
    {
        return view('admin.cash-registers.index', [
            'sessions' => CashRegisterSession::query()->with(['terminal', 'opener', 'closer'])->latest('opened_at')->paginate(25),
        ]);
    }

    public function show(CashRegisterSession $session, CashRegisterService $cashRegisterService)
    {
        return view('admin.cash-registers.show', [
            'session' => $session->load(['terminal', 'opener', 'closer', 'movements.paymentMethod', 'orders']),
            'summary' => $cashRegisterService->summary($session),
        ]);
    }
}
