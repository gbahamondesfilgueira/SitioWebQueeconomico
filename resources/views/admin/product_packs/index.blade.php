@extends('layouts.admin')
@section('title','Packs')
@section('page-title','Packs')
@section('content')
<div class="d-flex justify-content-between mb-3"><h1 class="h4">Packs</h1><a class="btn btn-primary" href="{{ route('admin.product-packs.create') }}">Crear pack</a></div>
<form class="card border-0 shadow-sm mb-3"><div class="card-body"><div class="input-group"><input class="form-control" name="search" value="{{ $search }}" placeholder="Buscar..."><button class="btn btn-outline-secondary">Buscar</button></div></div></form>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Nombre</th><th>SKU</th><th>Precio pack</th><th>Items</th><th>Visible</th><th>Activo</th><th></th></tr></thead><tbody>@forelse($packs as $pack)<tr><td>{{ $pack->name }}</td><td>{{ $pack->sku ?? '-' }}</td><td>${{ number_format($pack->pack_price,0,',','.') }}</td><td>{{ $pack->items_count }}</td><td>{{ $pack->is_visible?'Sí':'No' }}</td><td>{{ $pack->is_active?'Sí':'No' }}</td><td class="text-end"><a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.product-packs.show',$pack) }}">Ver</a><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.product-packs.edit',$pack) }}">Editar</a></td></tr>@empty<tr><td colspan="7" class="text-center text-secondary py-4">Sin packs.</td></tr>@endforelse</tbody></table></div></div>
@endsection
