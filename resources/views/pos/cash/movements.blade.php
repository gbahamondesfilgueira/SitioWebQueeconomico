@extends('layouts.pos')
@section('title', 'Movimientos de caja')
@section('content')
<h1 class="h4 mb-3">Movimientos de caja</h1>
<div class="bg-white border rounded p-3 table-responsive">
<table class="table align-middle"><thead><tr><th>Fecha</th><th>Tipo</th><th>Monto</th><th>Medio</th><th>Descripción</th><th>Usuario</th></tr></thead><tbody>
@foreach($session->movements as $movement)
<tr><td>{{ $movement->created_at->format('d/m/Y H:i') }}</td><td>{{ $movement->movement_type }}</td><td>${{ number_format($movement->amount, 0, ',', '.') }}</td><td>{{ $movement->paymentMethod?->name ?? '-' }}</td><td>{{ $movement->description }}</td><td>{{ $movement->user?->name }}</td></tr>
@endforeach
</tbody></table>
</div>
@endsection
