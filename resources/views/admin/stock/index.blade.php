@extends('layouts.admin')

@section('title', 'Stock actual')
@section('page-title', 'Stock actual')

@section('content')
    <form class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="input-group">
                <input class="form-control" name="search" value="{{ $search }}" placeholder="Buscar producto o SKU...">
                <button class="btn btn-outline-secondary">Buscar</button>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Variante</th>
                        <th>Bodega</th>
                        <th>Ubicacion</th>
                        <th>Fisico</th>
                        <th>Reservado</th>
                        <th>Disponible</th>
                        <th>Minimo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($levels as $level)
                        <tr>
                            <td>{{ $level->product->name }}</td>
                            <td>{{ $level->variant?->sku ?? '-' }}</td>
                            <td>{{ $level->warehouse->name }}</td>
                            <td>{{ $level->location?->name ?? '-' }}</td>
                            <td>{{ (int) $level->physical_stock }}</td>
                            <td>{{ (int) $level->reserved_stock }}</td>
                            <td class="{{ $level->available_stock <= $level->minimum_stock ? 'text-danger fw-semibold' : '' }}">{{ (int) $level->available_stock }}</td>
                            <td>{{ (int) $level->minimum_stock }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-secondary py-4">No hay stock registrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($levels->hasPages())
            <div class="card-footer bg-white">{{ $levels->links() }}</div>
        @endif
    </div>
@endsection
