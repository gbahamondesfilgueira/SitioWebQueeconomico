@extends('layouts.admin')
@section('title',$item->name ?? $item->code)
@section('page-title','Detalle')
@section('content')<div class="card border-0 shadow-sm"><div class="card-body"><dl class="row mb-0">@foreach($config['columns'] as $key=>$label)<dt class="col-sm-3">{{ $label }}</dt><dd class="col-sm-9">{{ is_bool($item->{$key}) ? ($item->{$key}?'Sí':'No') : ($item->{$key} ?? '-') }}</dd>@endforeach</dl><div class="mt-3"><a class="btn btn-outline-secondary" href="{{ route($config['routePrefix'].'.index') }}">Volver</a><a class="btn btn-primary" href="{{ route($config['routePrefix'].'.edit',$item) }}">Editar</a></div></div></div>@endsection
