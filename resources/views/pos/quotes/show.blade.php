@extends('layouts.pos')
@section('title', 'Cotización POS')
@section('content')
<div class="bg-white border rounded p-3">
<div class="d-flex justify-content-between"><div><h1 class="h4">{{ $quote->quote_number }}</h1><p class="text-muted">{{ $quote->customer?->display_name ?? 'Cliente mostrador' }}</p></div><form method="POST" action="{{ route('pos.quotes.convert', $quote) }}">@csrf<button class="btn btn-success">Convertir a venta</button></form></div>
<table class="table"><thead><tr><th>Ítem</th><th>Cantidad</th><th>Total</th></tr></thead><tbody>
@foreach($quote->items as $item)<tr><td>{{ $item->item_type === 'pack' ? $item->pack?->name : $item->product?->name }} {{ $item->variant?->name }}</td><td>{{ $item->quantity }}</td><td>${{ number_format($item->line_total, 0, ',', '.') }}</td></tr>@endforeach
</tbody></table>
<div class="text-end h4">Total: ${{ number_format($quote->grand_total, 0, ',', '.') }}</div>
</div>
@endsection
