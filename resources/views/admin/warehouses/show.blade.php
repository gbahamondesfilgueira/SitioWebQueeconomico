@extends('layouts.admin')
@section('title',$warehouse->name)
@section('page-title','Detalle de bodega')
@section('content')
<div class="d-flex justify-content-between mb-3"><h1 class="h4">{{ $warehouse->name }}</h1><div class="d-flex gap-2"><a class="btn btn-outline-secondary" href="{{ route('admin.warehouses.index') }}">Volver</a><a class="btn btn-primary" href="{{ route('admin.warehouses.locations.index',$warehouse) }}">Ubicaciones</a></div></div>
<div class="card border-0 shadow-sm"><div class="card-body"><dl class="row mb-0"><dt class="col-sm-3">Código</dt><dd class="col-sm-9">{{ $warehouse->code }}</dd><dt class="col-sm-3">Tipo</dt><dd class="col-sm-9">{{ $warehouse->type }}</dd><dt class="col-sm-3">Dirección</dt><dd class="col-sm-9">{{ $warehouse->address ?? '-' }}</dd><dt class="col-sm-3">Estado</dt><dd class="col-sm-9">{{ $warehouse->is_active ? 'Activa':'Inactiva' }}</dd></dl></div></div>
@endsection
