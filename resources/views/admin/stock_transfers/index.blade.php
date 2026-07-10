@extends('layouts.admin')
@section('title','Transferencias')
@section('page-title','Transferencias')
@section('content')
<div class="d-flex justify-content-between mb-3"><h1 class="h4">Transferencias</h1>@if(auth()->user()->hasRole(['super-admin','administrador']))<a class="btn btn-primary" href="{{ route('admin.stock-transfers.create') }}">Crear transferencia</a>@endif</div>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>#</th><th>Origen</th><th>Destino</th><th>Items</th><th>Estado</th><th>Fecha</th><th></th></tr></thead><tbody>@forelse($transfers as $transfer)<tr><td>{{ $transfer->id }}</td><td>{{ $transfer->originWarehouse->name }}</td><td>{{ $transfer->destinationWarehouse->name }}</td><td>{{ $transfer->items->count() }}</td><td>{{ $transfer->status }}</td><td>{{ $transfer->created_at->format('d/m/Y H:i') }}</td><td class="text-end"><a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.stock-transfers.show',$transfer) }}">Ver</a></td></tr>@empty<tr><td colspan="7" class="text-center text-secondary py-4">Sin transferencias.</td></tr>@endforelse</tbody></table></div></div>
@endsection
