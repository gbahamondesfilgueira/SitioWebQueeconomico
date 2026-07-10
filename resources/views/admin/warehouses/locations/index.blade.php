@extends('layouts.admin')
@section('title','Ubicaciones')
@section('page-title','Ubicaciones')
@section('content')
<div class="d-flex justify-content-between mb-3"><h1 class="h4">{{ $warehouse->name }} / Ubicaciones</h1><a class="btn btn-primary" href="{{ route('admin.warehouses.locations.create',$warehouse) }}">Crear ubicación</a></div>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Nombre</th><th>Código</th><th>Descripción</th><th>Estado</th><th></th></tr></thead><tbody>@foreach($warehouse->locations as $location)<tr><td>{{ $location->name }}</td><td>{{ $location->code }}</td><td>{{ $location->description }}</td><td>{{ $location->is_active?'Activa':'Inactiva' }}</td><td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.warehouses.locations.edit',[$warehouse,$location]) }}">Editar</a></td></tr>@endforeach</tbody></table></div></div>
@endsection
