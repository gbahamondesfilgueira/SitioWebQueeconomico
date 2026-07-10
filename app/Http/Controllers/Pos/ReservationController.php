<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pos\Concerns\ResolvesPosTerminal;
use App\Models\PosReservation;
use App\Services\PosService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    use ResolvesPosTerminal;

    public function index()
    {
        return view('pos.reservations.index', ['reservations' => PosReservation::query()->with(['customer', 'terminal'])->latest()->paginate(20)]);
    }

    public function createFromCart(Request $request, PosService $posService)
    {
        $cart = $posService->getActiveCart($this->terminal($request)->id, $request->user()->id);
        $reservation = $posService->createReservationFromCart($cart);
        return redirect()->route('pos.reservations.show', $reservation)->with('success', 'Reserva creada.');
    }

    public function show(PosReservation $reservation)
    {
        return view('pos.reservations.show', ['reservation' => $reservation->load(['items.product', 'items.variant', 'customer', 'terminal'])]);
    }

    public function convert(PosReservation $reservation, PosService $posService)
    {
        $order = $posService->convertReservationToSale($reservation);
        return redirect()->route('pos.orders.receipt', $order)->with('success', 'Reserva convertida en venta.');
    }
}
