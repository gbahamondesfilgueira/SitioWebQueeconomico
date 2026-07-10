@extends('layouts.admin')
@section('title','Bodegas')
@section('page-title','Bodegas')
@section('content')
<div class="d-flex justify-content-between mb-3"><h1 class="h4">Bodegas</h1><a class="btn btn-primary" href="{{ route('admin.warehouses.create') }}">Crear bodega</a></div>
<form class="card border-0 shadow-sm mb-3"><div class="card-body"><div class="input-group"><input class="form-control" name="search" value="{{ $search }}" placeholder="Buscar..."><button class="btn btn-outline-secondary">Buscar</button></div></div></form>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>Nombre</th><th>Código</th><th>Tipo</th><th>Ciudad</th><th>Ubicaciones</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead><tbody>
@forelse($warehouses as $warehouse)<tr><td>{{ $warehouse->name }}</td><td>{{ $warehouse->code }}</td><td>{{ $warehouse->type }}</td><td>{{ $warehouse->city ?? '-' }}</td><td>{{ $warehouse->locations_count }}</td><td><span class="badge {{ $warehouse->is_active ? 'text-bg-success':'text-bg-secondary' }}">{{ $warehouse->is_active?'Activa':'Inactiva' }}</span></td><td class="text-end"><div class="btn-group"><a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.warehouses.show',$warehouse) }}">Ver</a><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.warehouses.edit',$warehouse) }}">Editar</a><a class="btn btn-sm btn-outline-dark" href="{{ route('admin.warehouses.locations.index',$warehouse) }}">Ubicaciones</a></div></td></tr>@empty<tr><td colspan="7" class="text-center text-secondary py-4">Sin bodegas.</td></tr>@endforelse
</tbody></table></div>@if($warehouses->hasPages())<div class="card-footer bg-white">{{ $warehouses->links() }}</div>@endif</div>
@endsection
