@extends('layouts.admin')
@section('title',$pack->name)
@section('page-title','Detalle pack')
@section('content')
<div class="card border-0 shadow-sm mb-3"><div class="card-body"><h1 class="h4">{{ $pack->name }}</h1><p>{{ $pack->description }}</p><p><strong>Precio pack:</strong> ${{ number_format($pack->pack_price,0,',','.') }}</p><p><strong>Stock calculado:</strong> {{ $pack->getAvailableStock() }}</p><a class="btn btn-primary" href="{{ route('admin.product-packs.edit',$pack) }}">Editar</a></div></div>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Producto</th><th>Variante</th><th>Cantidad</th></tr></thead><tbody>@foreach($pack->items as $item)<tr><td>{{ $item->product->name }}</td><td>{{ $item->variant?->sku ?? '-' }}</td><td>{{ $item->quantity }}</td></tr>@endforeach</tbody></table></div></div>
@endsection
