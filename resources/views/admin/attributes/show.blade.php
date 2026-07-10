@extends('layouts.admin')

@section('title', 'Valores de '.$attribute->name)
@section('page-title', 'Valores de atributo')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">{{ $attribute->name }}</h1>
        <a class="btn btn-outline-secondary" href="{{ route('admin.attributes.index') }}">Volver</a>
    </div>
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">{{ $editingValue ? 'Editar valor' : 'Crear valor' }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ $editingValue ? route('admin.attributes.values.update', [$attribute, $editingValue]) : route('admin.attributes.values.store', $attribute) }}" class="row g-3">
                        @csrf
                        @if ($editingValue) @method('PUT') @endif
                        <div class="col-12"><label class="form-label" for="value">Valor</label><input class="form-control" id="value" name="value" value="{{ old('value', $editingValue->value ?? '') }}" required></div>
                        <div class="col-12"><label class="form-label" for="slug">Slug</label><input class="form-control" id="slug" name="slug" value="{{ old('slug', $editingValue->slug ?? '') }}" placeholder="Se genera automáticamente"></div>
                        <div class="col-12"><label class="form-label" for="color_hex">Color HEX</label><input class="form-control" id="color_hex" name="color_hex" value="{{ old('color_hex', $editingValue->color_hex ?? '') }}" placeholder="#000000"></div>
                        <div class="col-12"><label class="form-label" for="sort_order">Orden</label><input class="form-control" id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $editingValue->sort_order ?? 0) }}"></div>
                        <div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $editingValue->is_active ?? true))><label class="form-check-label" for="is_active">Activo</label></div></div>
                        <div class="col-12"><button class="btn btn-primary w-100">Guardar valor</button></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead><tr><th>Valor</th><th>Slug</th><th>Color</th><th>Orden</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
                        <tbody>
                            @forelse ($attribute->values as $value)
                                <tr>
                                    <td>{{ $value->value }}</td><td>{{ $value->slug }}</td><td>{{ $value->color_hex ?? '-' }}</td><td>{{ $value->sort_order }}</td>
                                    <td><span class="badge {{ $value->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $value->is_active ? 'Activo' : 'Inactivo' }}</span></td>
                                    <td class="text-end"><div class="btn-group">
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.attributes.values.edit', [$attribute, $value]) }}"><i class="bi bi-pencil"></i></a>
                                        <form method="POST" action="{{ route('admin.attributes.values.toggle-active', [$attribute, $value]) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-warning"><i class="bi bi-person-dash"></i></button></form>
                                    </div></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-secondary py-4">No hay valores.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
