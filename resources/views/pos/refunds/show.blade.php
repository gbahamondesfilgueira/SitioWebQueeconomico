@extends('layouts.pos')
@section('title', 'Devolución registrada')
@section('content')
<div class="bg-white border rounded p-4">
<h1 class="h4">Devolución registrada</h1>
<p><strong>Venta:</strong> {{ $refund->order->order_number }}</p>
<p><strong>Monto:</strong> ${{ number_format($refund->refund_amount, 0, ',', '.') }}</p>
<p><strong>Motivo:</strong> {{ $refund->reason }}</p>
<p><strong>Procesado por:</strong> {{ $refund->processor?->name }}</p>
<a class="btn btn-primary" href="{{ route('pos.cash.current') }}">Volver a caja</a>
</div>
@endsection
