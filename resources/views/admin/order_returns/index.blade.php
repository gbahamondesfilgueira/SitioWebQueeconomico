@extends('layouts.admin')
@section('title', 'Devoluciones')
@section('page-title', 'Devoluciones')
@section('content')
    <div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Número</th><th>Pedido</th><th>Estado</th><th>Motivo</th><th></th></tr></thead><tbody>@forelse($returns as $return)<tr><td>{{ $return->return_number }}</td><td>{{ $return->order?->order_number }}</td><td>{{ $return->status }}</td><td>{{ $return->reason }}</td><td><a href="{{ route('admin.order-returns.show', $return) }}" class="btn btn-sm btn-outline-dark">Ver</a></td></tr>@empty<tr><td colspan="5" class="text-center text-secondary py-4">Sin devoluciones.</td></tr>@endforelse</tbody></table></div></div>
@endsection
