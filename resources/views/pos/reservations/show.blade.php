@extends('layouts.pos')
@section('title', 'Reserva POS')
@section('content')
<div class="bg-white border rounded p-3">
<div class="d-flex justify-content-between"><div><h1 class="h4">{{ $reservation->reservation_number }}</h1><p class="text-muted">{{ $reservation->customer?->display_name ?? 'Cliente mostrador' }} · {{ $reservation->status }}</p></div><form method="POST" action="{{ route('pos.reservations.convert', $reservation) }}">@csrf<button class="btn btn-success">Convertir a venta</button></form></div>
<table class="table"><thead><tr><th>Producto</th><th>Cantidad</th></tr></thead><tbody>
@foreach($reservation->items as $item)<tr><td>{{ $item->product?->name }} {{ $item->variant?->name }}</td><td>{{ $item->quantity }}</td></tr>@endforeach
</tbody></table>
</div>
@endsection
