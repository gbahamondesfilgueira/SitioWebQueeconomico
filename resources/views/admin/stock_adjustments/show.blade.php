@extends('layouts.admin')
@section('title','Ajuste #'.$adjustment->id)
@section('page-title','Detalle de ajuste')
@section('content')
<div class="card border-0 shadow-sm"><div class="card-body"><p><strong>Producto:</strong> {{ $adjustment->product->name }}</p><p><strong>Bodega:</strong> {{ $adjustment->warehouse->name }}</p><p><strong>Cantidad:</strong> {{ $adjustment->quantity }}</p><p><strong>Estado:</strong> {{ $adjustment->status }}</p><p><strong>Notas:</strong> {{ $adjustment->notes }}</p>@if($adjustment->status==='pending' && auth()->user()->hasRole(['super-admin','administrador']))<div class="d-flex gap-2"><form method="POST" action="{{ route('admin.stock-adjustments.approve',$adjustment) }}">@csrf @method('PATCH')<button class="btn btn-success">Aprobar</button></form><form method="POST" action="{{ route('admin.stock-adjustments.reject',$adjustment) }}">@csrf @method('PATCH')<button class="btn btn-outline-danger">Rechazar</button></form></div>@endif</div></div>
@endsection
