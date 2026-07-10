@extends('layouts.admin')
@section('title','Kardex')
@section('page-title','Kardex / Movimientos')
@section('content')
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>Fecha</th><th>Tipo</th><th>Producto</th><th>Variante</th><th>Bodega</th><th>Cantidad</th><th>Anterior</th><th>Nuevo</th><th>Usuario</th></tr></thead><tbody>
@forelse($movements as $movement)<tr><td>{{ $movement->created_at?->format('d/m/Y H:i') }}</td><td><span class="badge text-bg-light">{{ $movement->movement_type }}</span></td><td>{{ $movement->product->name }}</td><td>{{ $movement->variant?->sku ?? '-' }}</td><td>{{ $movement->warehouse->name }}</td><td>{{ $movement->quantity }}</td><td>{{ $movement->previous_stock }}</td><td>{{ $movement->new_stock }}</td><td>{{ $movement->user?->name ?? 'Sistema' }}</td></tr>@empty<tr><td colspan="9" class="text-center text-secondary py-4">Sin movimientos.</td></tr>@endforelse
</tbody></table></div>@if($movements->hasPages())<div class="card-footer bg-white">{{ $movements->links() }}</div>@endif</div>
@endsection
