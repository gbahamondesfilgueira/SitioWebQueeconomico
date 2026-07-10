@extends('layouts.admin')

@section('title', 'Variantes')
@section('page-title', 'Variantes de producto')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-0">{{ $product->name }}</h1>
            <div class="text-secondary small">Gestión de variantes</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('admin.products.edit', $product) }}">Volver al producto</a>
            <a class="btn btn-primary" href="{{ route('admin.products.variants.create', $product) }}">Crear variante</a>
        </div>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th>SKU</th><th>Barcode</th><th>Nombre</th><th>Atributos</th><th>Precio</th><th>Peso facturable</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
                <tbody>
                    @forelse ($variants as $variant)
                        <tr>
                            <td>{{ $variant->sku ?? '-' }}</td>
                            <td>{{ $variant->barcode ?? '-' }}</td>
                            <td>{{ $variant->name ?? '-' }}</td>
                            <td>{{ $variant->attributeValues->map(fn($v) => $v->attribute->name.': '.$v->value)->join(' / ') }}</td>
                            <td>{{ $variant->getFinalPrice() !== null ? '$'.number_format($variant->getFinalPrice(), 0, ',', '.') : '-' }}</td>
                            <td>{{ number_format($variant->getBillableWeight(), 3, ',', '.') }}</td>
                            <td><span class="badge {{ $variant->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $variant->is_active ? 'Activo' : 'Inactivo' }}</span></td>
                            <td class="text-end"><div class="btn-group">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.products.variants.edit', [$product, $variant]) }}"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.products.variants.toggle-active', [$product, $variant]) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-warning"><i class="bi bi-person-dash"></i></button></form>
                                <form method="POST" action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}" onsubmit="return confirm('¿Enviar variante a papelera?');">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-secondary py-4">Este producto todavía no tiene variantes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($variants->hasPages())<div class="card-footer bg-white">{{ $variants->links() }}</div>@endif
    </div>
@endsection
