@extends('layouts.pos')
@section('title', 'Reporte de caja')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <h1 class="h4">Reporte diario de caja</h1>
    <button class="btn btn-outline-secondary" onclick="window.print()">Imprimir</button>
</div>
<div class="bg-white border rounded p-3 mb-3">
    <div><strong>Terminal:</strong> {{ $session->terminal?->name }}</div>
    <div><strong>Vendedor:</strong> {{ $session->opener?->name }}</div>
    <div><strong>Apertura:</strong> {{ $session->opened_at->format('d/m/Y H:i') }}</div>
    <div><strong>Cierre:</strong> {{ $session->closed_at?->format('d/m/Y H:i') ?? 'Abierta' }}</div>
    <div><strong>Estado:</strong> {{ $session->status }}</div>
</div>
@include('pos.cash.partials.totals')
<div class="bg-white border rounded p-3 mt-3">
    <h2 class="h5">Resultado de cierre</h2>
    <div class="d-flex justify-content-between"><span>Efectivo contado</span><strong>${{ number_format($session->counted_cash_amount ?? 0, 0, ',', '.') }}</strong></div>
    <div class="d-flex justify-content-between"><span>Diferencia</span><strong>${{ number_format($session->cash_difference ?? 0, 0, ',', '.') }}</strong></div>
</div>
@endsection
