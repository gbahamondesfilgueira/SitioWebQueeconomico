@extends('layouts.admin')

@section('title', 'Atributos')
@section('page-title', 'Atributos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Atributos de productos</h1>
        <a class="btn btn-primary" href="{{ route('admin.attributes.create') }}">Crear atributo</a>
    </div>
    <form class="card border-0 shadow-sm mb-3" method="GET"><div class="card-body"><div class="input-group"><input class="form-control" name="search" value="{{ $search }}" placeholder="Buscar..."><button class="btn btn-outline-secondary">Buscar</button></div></div></form>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th>Nombre</th><th>Slug</th><th>Tipo</th><th>Valores</th><th>Orden</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
                <tbody>
                    @forelse ($attributes as $attribute)
                        <tr>
                            <td>{{ $attribute->name }}</td><td>{{ $attribute->slug }}</td><td>{{ $attribute->type }}</td><td>{{ $attribute->values_count }}</td><td>{{ $attribute->sort_order }}</td>
                            <td><span class="badge {{ $attribute->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $attribute->is_active ? 'Activo' : 'Inactivo' }}</span></td>
                            <td class="text-end"><div class="btn-group">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.attributes.show', $attribute) }}">Valores</a>
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.attributes.edit', $attribute) }}"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.attributes.toggle-active', $attribute) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-warning"><i class="bi bi-person-dash"></i></button></form>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-secondary py-4">No hay atributos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($attributes->hasPages())<div class="card-footer bg-white">{{ $attributes->links() }}</div>@endif
    </div>
@endsection
