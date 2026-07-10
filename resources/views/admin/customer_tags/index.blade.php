@extends('layouts.admin')

@section('title', 'Etiquetas cliente')
@section('page-title', 'Etiquetas cliente')

@section('content')
    <div class="row g-3">
        <div class="col-lg-4">
            <form method="POST" action="{{ route('admin.customer-tags.store') }}" class="card border-0 shadow-sm">
                @csrf
                <div class="card-header bg-white fw-semibold">Nueva etiqueta</div>
                <div class="card-body">
                    <label class="form-label">Nombre</label><input name="name" class="form-control mb-2" required>
                    <label class="form-label">Descripción</label><input name="description" class="form-control mb-2">
                    <label class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" checked> Activa</label>
                </div>
                <div class="card-footer bg-white"><button class="btn btn-dark">Crear</button></div>
            </form>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive"><table class="table mb-0 align-middle"><thead><tr><th>Etiqueta</th><th>Clientes</th><th>Estado</th><th>Editar rápido</th></tr></thead><tbody>
                    @foreach($tags as $tag)
                        <tr>
                            <td>{{ $tag->name }}</td><td>{{ $tag->customer_profiles_count }}</td><td>{{ $tag->is_active ? 'Activa' : 'Inactiva' }}</td>
                            <td><form method="POST" action="{{ route('admin.customer-tags.update', $tag) }}" class="row g-2">@csrf @method('PUT')<div class="col"><input name="name" class="form-control form-control-sm" value="{{ $tag->name }}"></div><div class="col"><input name="description" class="form-control form-control-sm" value="{{ $tag->description }}"></div><div class="col-auto"><label class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" @checked($tag->is_active)></label></div><div class="col-auto"><button class="btn btn-sm btn-outline-primary">Guardar</button></div></form></td>
                        </tr>
                    @endforeach
                </tbody></table></div>
                <div class="card-footer bg-white">{{ $tags->links() }}</div>
            </div>
        </div>
    </div>
@endsection
