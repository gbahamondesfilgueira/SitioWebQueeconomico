@extends('layouts.admin')

@section('title', $product->name)
@section('page-title', 'Ficha de producto')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">{{ $product->name }}</h1>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('admin.products.index') }}">Volver</a>
            <a class="btn btn-primary" href="{{ route('admin.products.edit', $product) }}">Editar</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Resumen</div>
                <div class="card-body">
                    <dl class="mb-0">
                        <dt>SKU</dt><dd>{{ $product->sku ?? '-' }}</dd>
                        <dt>Barcode</dt><dd>{{ $product->barcode ?? '-' }}</dd>
                        <dt>Tipo</dt><dd>{{ $product->product_type === 'variable' ? 'Variable' : 'Simple' }}</dd>
                        <dt>Precio final</dt><dd>{{ $product->getFinalPrice() !== null ? '$'.number_format($product->getFinalPrice(), 0, ',', '.') : '-' }}</dd>
                        <dt>Peso facturable</dt><dd>{{ number_format($product->getBillableWeight(), 3, ',', '.') }}</dd>
                        <dt>Estado</dt><dd>{{ $product->is_active ? 'Activo' : 'Inactivo' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Relaciones</div>
                <div class="card-body">
                    <p><strong>Categoría:</strong> {{ $product->category?->name ?? '-' }}</p>
                    <p><strong>Marca:</strong> {{ $product->brand?->name ?? '-' }}</p>
                    <p><strong>Proveedor:</strong> {{ $product->supplier?->name ?? '-' }}</p>
                    <p><strong>Etiquetas:</strong> {{ $product->tags->pluck('name')->join(', ') ?: '-' }}</p>
                    <p class="mb-0"><strong>Relacionados:</strong> {{ $product->relatedProducts->pluck('name')->join(', ') ?: '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-3">
        <div class="card-header bg-white fw-semibold">Variantes</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>SKU</th><th>Nombre</th><th>Atributos</th><th>Precio</th><th>Estado</th></tr></thead>
                <tbody>
                    @forelse ($product->variants as $variant)
                        <tr>
                            <td>{{ $variant->sku ?? '-' }}</td>
                            <td>{{ $variant->name ?? '-' }}</td>
                            <td>{{ $variant->attributeValues->map(fn($v) => $v->attribute->name.': '.$v->value)->join(' / ') }}</td>
                            <td>{{ $variant->getFinalPrice() !== null ? '$'.number_format($variant->getFinalPrice(), 0, ',', '.') : '-' }}</td>
                            <td>{{ $variant->is_active ? 'Activo' : 'Inactivo' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-4">Sin variantes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
