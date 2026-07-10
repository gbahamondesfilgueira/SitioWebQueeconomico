@extends('layouts.pos')
@section('title', 'Caja actual')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h1 class="h4 mb-0">Caja actual</h1><div class="text-muted">{{ $terminal->name }} · Apertura {{ $session->opened_at->format('d/m/Y H:i') }}</div></div>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-primary" href="{{ route('pos.cash.income') }}">Ingreso</a>
        <a class="btn btn-outline-warning" href="{{ route('pos.cash.expense') }}">Egreso / retiro</a>
        <a class="btn btn-outline-secondary" href="{{ route('pos.cash.report') }}">Reporte</a>
        <a class="btn btn-danger" href="{{ route('pos.cash.close') }}">Cerrar caja</a>
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Monto inicial</div><div class="h3">${{ number_format($summary['opening'], 0, ',', '.') }}</div></div></div>
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Ventas</div><div class="h3">${{ number_format($summary['sales'], 0, ',', '.') }}</div></div></div>
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Efectivo esperado</div><div class="h3">${{ number_format($summary['cashExpected'], 0, ',', '.') }}</div></div></div>
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Estado</div><div class="h3">{{ $session->status === 'open' ? 'Abierta' : 'Cerrada' }}</div></div></div>
</div>
@include('pos.cash.partials.totals')
@endsection
