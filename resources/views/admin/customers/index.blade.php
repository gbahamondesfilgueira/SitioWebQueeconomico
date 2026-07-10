@extends('layouts.admin')

@section('title', 'Clientes')
@section('page-title', 'CRM de Clientes')

@section('content')
    <div class="row g-3 mb-4">
        @foreach ([['Registrados', $stats['total']], ['Activos', $stats['active']], ['Nuevos', $stats['new']], ['VIP', $stats['vip']], ['Empresas', $stats['companies']], ['Newsletter', $stats['newsletter']], ['Inactivos', $stats['inactive']]] as [$label, $value])
            <div class="col-md-2">
                <div class="card border-0 shadow-sm"><div class="card-body"><div class="text-secondary small">{{ $label }}</div><div class="fs-4 fw-bold">{{ $value }}</div></div></div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex gap-2 align-items-center">
            <form class="d-flex gap-2 flex-grow-1">
                <input class="form-control" name="search" value="{{ $search }}" placeholder="Buscar por nombre, email, RUT o empresa">
                <button class="btn btn-outline-secondary">Buscar</button>
            </form>
            <a href="{{ route('admin.customers.create') }}" class="btn btn-dark"><i class="bi bi-plus-lg me-1"></i>Crear cliente</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th>Cliente</th><th>Tipo</th><th>Contacto</th><th>Etiquetas</th><th>Puntos</th><th>Estado</th><th></th></tr></thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td><div class="fw-semibold">{{ $customer->display_name }}</div><div class="small text-secondary">{{ $customer->rut }}</div></td>
                            <td>{{ $customer->customer_type === 'company' ? 'Empresa' : 'Particular' }}</td>
                            <td><div>{{ $customer->email }}</div><div class="small text-secondary">{{ $customer->phone }}</div></td>
                            <td>@foreach($customer->tags as $tag)<span class="badge text-bg-light">{{ $tag->name }}</span> @endforeach</td>
                            <td>{{ number_format((float) $customer->reward_points, 0, ',', '.') }}</td>
                            <td><span class="badge {{ $customer->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $customer->is_active ? 'Activo' : 'Inactivo' }}</span></td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.customers.show', $customer) }}">Ver</a>
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.customers.edit', $customer) }}">Editar</a>
                                <form class="d-inline" method="POST" action="{{ route('admin.customers.toggle-active', $customer) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-warning">{{ $customer->is_active ? 'Desactivar' : 'Activar' }}</button></form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-secondary py-4">No hay clientes registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">{{ $customers->links() }}</div>
    </div>
@endsection
