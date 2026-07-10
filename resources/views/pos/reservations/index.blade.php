@extends('layouts.pos')
@section('title', 'Reservas POS')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="h4">Reservas POS</h1><a class="btn btn-primary" href="{{ route('pos.sale.create') }}">Nueva venta</a></div>
<div class="bg-white border rounded p-3 table-responsive">
<table class="table align-middle"><thead><tr><th>Número</th><th>Cliente</th><th>Estado</th><th>Vence</th><th></th></tr></thead><tbody>
@forelse($reservations as $reservation)
<tr><td>{{ $reservation->reservation_number }}</td><td>{{ $reservation->customer?->display_name ?? 'Mostrador' }}</td><td>{{ $reservation->status }}</td><td>{{ $reservation->expires_at?->format('d/m/Y H:i') }}</td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('pos.reservations.show', $reservation) }}">Ver</a></td></tr>
@empty <tr><td colspan="5" class="text-muted">Sin reservas.</td></tr> @endforelse
</tbody></table>{{ $reservations->links() }}</div>
@endsection
