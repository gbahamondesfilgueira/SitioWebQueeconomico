@extends('layouts.pos')

@section('title', 'Dashboard POS')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-0">Dashboard POS</h1>
        <div class="text-muted">Terminal: {{ $terminal->name }} / {{ $terminal->code }}</div>
    </div>
    <a href="{{ route('pos.sale.create') }}" class="btn btn-primary btn-lg">Nueva venta</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Ventas del día</div><div class="h2">{{ $salesToday }}</div></div></div>
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Total vendido</div><div class="h2">${{ number_format($totalToday, 0, ',', '.') }}</div></div></div>
    <div class="col-md-2"><div class="bg-white border rounded p-3"><div class="text-muted">Cotizaciones</div><div class="h2">{{ $openQuotes }}</div></div></div>
    <div class="col-md-2"><div class="bg-white border rounded p-3"><div class="text-muted">Reservas</div><div class="h2">{{ $activeReservations }}</div></div></div>
    <div class="col-md-2"><div class="bg-white border rounded p-3"><div class="text-muted">Bajo stock</div><div class="h2">{{ $lowStock }}</div></div></div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Caja</div><div class="h3">{{ $cashSession ? 'Abierta' : 'Cerrada' }}</div><a href="{{ route($cashSession ? 'pos.cash.current' : 'pos.cash.open') }}" class="btn btn-sm btn-outline-primary">{{ $cashSession ? 'Ver caja' : 'Abrir caja' }}</a></div></div>
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Monto inicial</div><div class="h3">${{ number_format($cashSummary['opening'] ?? 0, 0, ',', '.') }}</div></div></div>
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Efectivo esperado</div><div class="h3">${{ number_format($cashSummary['cashExpected'] ?? 0, 0, ',', '.') }}</div></div></div>
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Diferencia</div><div class="h3">${{ number_format($cashSession?->cash_difference ?? 0, 0, ',', '.') }}</div></div></div>
</div>

<div class="bg-white border rounded p-3">
    <h2 class="h5">Últimas ventas</h2>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Número</th><th>Cliente</th><th>Total</th><th>Fecha</th><th></th></tr></thead>
            <tbody>
            @forelse($latestSales as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>${{ number_format($order->grand_total, 0, ',', '.') }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td><a class="btn btn-sm btn-outline-secondary" href="{{ route('pos.orders.receipt', $order) }}">Comprobante</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-muted">Sin ventas POS todavía.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
