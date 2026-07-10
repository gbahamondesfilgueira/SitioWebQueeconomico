@extends('layouts.pos')
@section('title', 'Cotizaciones POS')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="h4">Cotizaciones POS</h1><a class="btn btn-primary" href="{{ route('pos.sale.create') }}">Nueva venta</a></div>
<div class="bg-white border rounded p-3 table-responsive">
<table class="table align-middle"><thead><tr><th>Número</th><th>Cliente</th><th>Total</th><th>Estado</th><th>Vence</th><th></th></tr></thead><tbody>
@forelse($quotes as $quote)
<tr><td>{{ $quote->quote_number }}</td><td>{{ $quote->customer?->display_name ?? 'Mostrador' }}</td><td>${{ number_format($quote->grand_total, 0, ',', '.') }}</td><td>{{ $quote->status }}</td><td>{{ $quote->expires_at?->format('d/m/Y') }}</td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('pos.quotes.show', $quote) }}">Ver</a></td></tr>
@empty <tr><td colspan="6" class="text-muted">Sin cotizaciones.</td></tr> @endforelse
</tbody></table>{{ $quotes->links() }}</div>
@endsection
