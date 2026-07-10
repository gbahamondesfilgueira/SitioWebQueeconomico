@extends('layouts.pos')
@section('title', 'Anulación POS')
@section('content')
<div class="bg-white border rounded p-4">
<h1 class="h4">Anulación registrada</h1>
<p><strong>Venta:</strong> {{ $cancellation->order->order_number }}</p>
<p><strong>Motivo:</strong> {{ $cancellation->reason }}</p>
<p><strong>Usuario:</strong> {{ $cancellation->user?->name }}</p>
<p><strong>Fecha:</strong> {{ $cancellation->cancelled_at->format('d/m/Y H:i') }}</p>
<a class="btn btn-primary" href="{{ route('pos.cash.current') }}">Volver a caja</a>
</div>
@endsection
