<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockReservation;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StockReservationController extends Controller
{
    public function index(): View
    {
        return view('admin.stock_reservations.index', ['reservations' => StockReservation::with(['warehouse', 'product', 'variant'])->latest()->paginate(20)]);
    }
    public function release(StockReservation $stock_reservation, InventoryService $inventory): RedirectResponse
    {
        $inventory->releaseReservation($stock_reservation);
        return back()->with('success', 'Reserva liberada.');
    }
    public function consume(StockReservation $stock_reservation, InventoryService $inventory): RedirectResponse
    {
        $inventory->consumeReservation($stock_reservation);
        return back()->with('success', 'Reserva consumida.');
    }
}
