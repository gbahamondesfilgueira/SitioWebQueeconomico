@extends('layouts.admin')

@section('title', $config['title'])
@section('page-title', $config['title'])

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">{{ $config['title'] }}</h1>
        <a class="btn btn-primary" href="{{ route($config['routePrefix'].'.create') }}">
            <i class="bi bi-plus-lg me-1"></i>Crear
        </a>
    </div>

    <form class="card border-0 shadow-sm mb-3" method="GET">
        <div class="card-body">
            <div class="input-group">
                <input class="form-control" name="search" value="{{ $search }}" placeholder="Buscar...">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        @foreach ($config['columns'] as $label)
                            <th>{{ $label }}</th>
                        @endforeach
                        @if ($config['hasActiveToggle'])
                            <th>Estado</th>
                        @endif
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            @foreach ($config['columns'] as $key => $label)
                                <td>
                                    @if (is_bool($item->{$key}))
                                        <span class="badge {{ $item->{$key} ? 'text-bg-primary' : 'text-bg-light' }}">{{ $item->{$key} ? 'Sí' : 'No' }}</span>
                                    @elseif ($key === 'percentage')
                                        {{ number_format((float) $item->{$key}, 2, ',', '.') }}%
                                    @elseif ($key === 'type')
                                        {{ $config['typeOptions'][$item->{$key}] ?? $item->{$key} }}
                                    @else
                                        {{ $item->{$key} ?? '-' }}
                                    @endif
                                </td>
                            @endforeach
                            @if ($config['hasActiveToggle'])
                                <td>
                                    <span class="badge {{ $item->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ $item->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                            @endif
                            <td class="text-end">
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route($config['routePrefix'].'.edit', $item) }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if ($config['hasActiveToggle'])
                                        <form method="POST" action="{{ route($config['routePrefix'].'.toggle-active', $item) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm {{ $item->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" type="submit">
                                                <i class="bi {{ $item->is_active ? 'bi-person-dash' : 'bi-person-check' }}"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if ($config['allowDelete'])
                                        <form method="POST" action="{{ route($config['routePrefix'].'.destroy', $item) }}" onsubmit="return confirm('¿Eliminar este registro?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($config['columns']) + ($config['hasActiveToggle'] ? 2 : 1) }}" class="text-center text-secondary py-4">
                                No hay registros disponibles.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($items->hasPages())
            <div class="card-footer bg-white">{{ $items->links() }}</div>
        @endif
    </div>
@endsection
