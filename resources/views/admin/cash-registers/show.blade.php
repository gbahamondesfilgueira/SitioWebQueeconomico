@extends('layouts.admin')
@section('title', 'Detalle caja POS')
@section('content')
<h1 class="h4 mb-3">Caja #{{ $session->id }} · {{ $session->terminal?->name }}</h1>
<div class="row g-3 mb-3">
<div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Estado</div><div class="h4">{{ $session->status }}</div></div></div></div>
<div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Inicial</div><div class="h4">${{ number_format($session->opening_amount, 0, ',', '.') }}</div></div></div></div>
<div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Esperado</div><div class="h4">${{ number_format($summary['cashExpected'], 0, ',', '.') }}</div></div></div></div>
<div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Diferencia</div><div class="h4">${{ number_format($session->cash_difference ?? 0, 0, ',', '.') }}</div></div></div></div>
</div>
<div class="card"><div class="card-body table-responsive">
<table class="table"><thead><tr><th>Fecha</th><th>Tipo</th><th>Monto</th><th>Medio</th><th>Descripción</th></tr></thead><tbody>
@foreach($session->movements as $movement)<tr><td>{{ $movement->created_at->format('d/m/Y H:i') }}</td><td>{{ $movement->movement_type }}</td><td>${{ number_format($movement->amount, 0, ',', '.') }}</td><td>{{ $movement->paymentMethod?->name }}</td><td>{{ $movement->description }}</td></tr>@endforeach
</tbody></table>
</div></div>
@endsection
