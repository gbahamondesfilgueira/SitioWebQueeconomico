<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pos\Concerns\ResolvesPosTerminal;
use App\Models\Order;
use App\Models\PosQuote;
use App\Models\PosReservation;
use App\Models\StockLevel;
use App\Services\AuditLogger;
use App\Services\CashRegisterService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ResolvesPosTerminal;

    public function __invoke(Request $request, CashRegisterService $cashRegisterService)
    {
        $terminal = $this->terminal($request);
        $todaySales = Order::query()->where('order_channel', 'pos')->where('sold_by', $request->user()->id)->whereDate('created_at', today());

        AuditLogger::record('opened', 'pos', 'POS abierto');
        $cashSession = $cashRegisterService->getOpenSession($terminal->id);

        return view('pos.dashboard', [
            'terminal' => $terminal,
            'salesToday' => (clone $todaySales)->count(),
            'totalToday' => (clone $todaySales)->sum('grand_total'),
            'latestSales' => Order::query()->where('order_channel', 'pos')->latest()->limit(8)->get(),
            'openQuotes' => PosQuote::query()->whereIn('status', ['draft', 'sent'])->count(),
            'activeReservations' => PosReservation::query()->where('status', 'active')->count(),
            'lowStock' => StockLevel::query()->where('warehouse_id', $terminal->warehouse_id)->whereColumn('physical_stock', '<=', 'minimum_stock')->count(),
            'cashSession' => $cashSession,
            'cashSummary' => $cashSession ? $cashRegisterService->summary($cashSession) : null,
        ]);
    }
}
