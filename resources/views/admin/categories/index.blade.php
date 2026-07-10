@extends('layouts.admin')

@section('title', 'Categorías')
@section('page-title', 'Categorías')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Categorías</h1>
        <a class="btn btn-primary" href="{{ route('admin.categories.create') }}">
            <i class="bi bi-plus-lg me-1"></i>Crear categoría
        </a>
    </div>

    <form class="card border-0 shadow-sm mb-3" method="GET">
        <div class="card-body">
            <div class="input-group">
                <input class="form-control" name="search" value="{{ $search }}" placeholder="Buscar por nombre, slug o descripción...">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Orden</th>
                        <th>Nombre</th>
                        <th>Slug</th>
                        <th>Padre</th>
                        <th>Hijas</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->sort_order }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->parent?->name ?? '-' }}</td>
                            <td>{{ $category->children_count }}</td>
                            <td>
                                <span class="badge {{ $category->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ $category->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.categories.edit', $category) }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.categories.toggle-active', $category) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm {{ $category->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" type="submit">
                                            <i class="bi {{ $category->is_active ? 'bi-person-dash' : 'bi-person-check' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('¿Eliminar esta categoría?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit" @disabled($category->children_count > 0)>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-secondary py-4">No hay categorías registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($categories->hasPages())
            <div class="card-footer bg-white">{{ $categories->links() }}</div>
        @endif
    </div>
@endsection
