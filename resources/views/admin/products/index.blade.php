@extends('layouts.admin')

@section('title', 'Productos')
@section('page-title', 'Productos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Productos inteligentes</h1>
        <a class="btn btn-primary" href="{{ route('admin.products.create') }}"><i class="bi bi-plus-lg me-1"></i>Crear producto</a>
    </div>

    <form class="card border-0 shadow-sm mb-3" method="GET">
        <div class="card-body">
            <div class="input-group">
                <input class="form-control" name="search" value="{{ $search }}" placeholder="Buscar por nombre, SKU, barcode o slug...">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>SKU</th>
                        <th>Tipo</th>
                        <th>Categoría</th>
                        <th>Marca</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Destacado</th>
                        <th>Visible</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        @php $primary = $product->images->firstWhere('is_primary', true) ?? $product->images->first(); @endphp
                        <tr>
                            <td>
                                @if ($primary)
                                    <img src="{{ asset('storage/'.$primary->image_path) }}" alt="{{ $product->name }}" width="52" height="52" class="rounded object-fit-cover">
                                @else
                                    <span class="badge text-bg-light"><i class="bi bi-image"></i></span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $product->name }}</div>
                                <div class="small text-secondary">{{ $product->variants_count }} variantes</div>
                            </td>
                            <td>{{ $product->sku ?? '-' }}</td>
                            <td><span class="badge text-bg-light">{{ $product->product_type === 'variable' ? 'Variable' : 'Simple' }}</span></td>
                            <td>{{ $product->category?->name ?? '-' }}</td>
                            <td>{{ $product->brand?->name ?? '-' }}</td>
                            <td>
                                @if ($product->hasActiveSale())
                                    <span class="text-danger fw-semibold">${{ number_format($product->getFinalPrice(), 0, ',', '.') }}</span>
                                    <span class="small text-secondary text-decoration-line-through">${{ number_format((float) $product->regular_price, 0, ',', '.') }}</span>
                                @elseif ($product->regular_price)
                                    ${{ number_format((float) $product->regular_price, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td><span class="badge {{ $product->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $product->is_active ? 'Activo' : 'Inactivo' }}</span></td>
                            <td>{{ $product->is_featured ? 'Sí' : 'No' }}</td>
                            <td>{{ $product->is_visible ? 'Sí' : 'No' }}</td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.products.show', $product) }}"><i class="bi bi-eye"></i></a>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.products.edit', $product) }}"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('admin.products.toggle-active', $product) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm {{ $product->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" type="submit">
                                            <i class="bi {{ $product->is_active ? 'bi-person-dash' : 'bi-person-check' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="11" class="text-center text-secondary py-4">No hay productos creados todavía.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($products->hasPages())
            <div class="card-footer bg-white">{{ $products->links() }}</div>
        @endif
    </div>
@endsection
