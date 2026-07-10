@extends('layouts.admin')

@section('title', 'Marcas')
@section('page-title', 'Marcas')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Marcas</h1>
        <a class="btn btn-primary" href="{{ route('admin.brands.create') }}">
            <i class="bi bi-plus-lg me-1"></i>Crear marca
        </a>
    </div>

    <form class="card border-0 shadow-sm mb-3" method="GET">
        <div class="card-body">
            <div class="input-group">
                <input class="form-control" name="search" value="{{ $search }}" placeholder="Buscar por nombre, slug o sitio web...">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Slug</th>
                        <th>Sitio web</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($brands as $brand)
                        <tr>
                            <td>{{ $brand->name }}</td>
                            <td>{{ $brand->slug }}</td>
                            <td>{{ $brand->website ? parse_url($brand->website, PHP_URL_HOST) : '-' }}</td>
                            <td>
                                <span class="badge {{ $brand->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ $brand->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.brands.edit', $brand) }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.brands.toggle-active', $brand) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm {{ $brand->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" type="submit">
                                            <i class="bi {{ $brand->is_active ? 'bi-person-dash' : 'bi-person-check' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" onsubmit="return confirm('¿Eliminar esta marca?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-4">No hay marcas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($brands->hasPages())
            <div class="card-footer bg-white">{{ $brands->links() }}</div>
        @endif
    </div>
@endsection
