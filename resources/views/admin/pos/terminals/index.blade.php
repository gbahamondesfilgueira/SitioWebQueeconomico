@extends('layouts.admin')
@section('title', 'Terminales POS')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="h4">Terminales POS</h1><a class="btn btn-primary" href="{{ route('admin.pos.terminals.create') }}">Crear terminal</a></div>
<div class="card"><div class="card-body table-responsive"><table class="table align-middle"><thead><tr><th>Nombre</th><th>Código</th><th>Bodega</th><th>Estado</th><th></th></tr></thead><tbody>
@forelse($terminals as $terminal)
<tr><td>{{ $terminal->name }}</td><td>{{ $terminal->code }}</td><td>{{ $terminal->warehouse?->name }}</td><td><span class="badge bg-{{ $terminal->is_active ? 'success' : 'secondary' }}">{{ $terminal->is_active ? 'Activo' : 'Inactivo' }}</span></td><td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.pos.terminals.edit', $terminal) }}">Editar</a><form method="POST" action="{{ route('admin.pos.terminals.toggle', $terminal) }}" class="d-inline">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-secondary">Activar/desactivar</button></form></td></tr>
@empty <tr><td colspan="5" class="text-muted">Sin terminales.</td></tr> @endforelse
</tbody></table>{{ $terminals->links() }}</div></div>
@endsection
