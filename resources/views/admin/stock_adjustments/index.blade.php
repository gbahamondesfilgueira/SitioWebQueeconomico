@extends('layouts.admin')
@section('title','Ajustes')
@section('page-title','Ajustes de inventario')
@section('content')
<div class="d-flex justify-content-between mb-3"><h1 class="h4">Ajustes</h1><a class="btn btn-primary" href="{{ route('admin.stock-adjustments.create') }}">Crear ajuste</a></div>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>#</th><th>Producto</th><th>Bodega</th><th>Tipo</th><th>Cantidad</th><th>Motivo</th><th>Estado</th><th></th></tr></thead><tbody>@foreach($adjustments as $adjustment)<tr><td>{{ $adjustment->id }}</td><td>{{ $adjustment->product->name }}</td><td>{{ $adjustment->warehouse->name }}</td><td>{{ $adjustment->adjustment_type }}</td><td>{{ $adjustment->quantity }}</td><td>{{ $adjustment->reason }}</td><td>{{ $adjustment->status }}</td><td class="text-end"><a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.stock-adjustments.show',$adjustment) }}">Ver</a></td></tr>@endforeach</tbody></table></div></div>
@endsection
