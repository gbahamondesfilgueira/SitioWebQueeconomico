@extends('layouts.admin')
@section('title','Detalle stock')
@section('page-title','Detalle stock')
@section('content')<div class="card border-0 shadow-sm"><div class="card-body"><dl class="row mb-0"><dt class="col-sm-3">Producto</dt><dd class="col-sm-9">{{ $level->product->name }}</dd><dt class="col-sm-3">Bodega</dt><dd class="col-sm-9">{{ $level->warehouse->name }}</dd><dt class="col-sm-3">Físico</dt><dd class="col-sm-9">{{ $level->physical_stock }}</dd><dt class="col-sm-3">Reservado</dt><dd class="col-sm-9">{{ $level->reserved_stock }}</dd><dt class="col-sm-3">Disponible</dt><dd class="col-sm-9">{{ $level->available_stock }}</dd></dl></div></div>@endsection
