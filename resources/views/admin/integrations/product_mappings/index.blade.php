@extends('layouts.admin')
@section('title','Mapeo productos')
@section('content')
<div class="d-flex justify-content-between mb-3"><h1 class="h4">Mapeo de productos</h1><a class="btn btn-primary" href="{{ route('admin.external-product-mappings.create') }}">Nuevo mapeo</a></div>
<div class="card"><div class="card-body table-responsive"><table class="table"><thead><tr><th>Integración</th><th>Producto</th><th>Externo</th><th>Stock</th><th>Precio</th><th></th></tr></thead><tbody>@foreach($mappings as $mapping)<tr><td>{{ $mapping->integration?->name }}</td><td>{{ $mapping->product?->name }} {{ $mapping->variant?->name }}</td><td>{{ $mapping->external_product_id }}</td><td>{{ $mapping->sync_stock ? 'Sí' : 'No' }}</td><td>{{ $mapping->sync_price ? 'Sí' : 'No' }}</td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.external-product-mappings.edit',$mapping) }}">Editar</a></td></tr>@endforeach</tbody></table>{{ $mappings->links() }}</div></div>
@endsection
