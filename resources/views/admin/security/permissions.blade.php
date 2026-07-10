@extends('layouts.admin')
@section('title', 'Permisos')
@section('page-title', 'Permisos')
@section('content')
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.security.permissions.store') }}" class="row g-3">
                @csrf
                <div class="col-md-3"><input name="name" class="form-control" placeholder="Nombre" required></div>
                <div class="col-md-3"><input name="slug" class="form-control" placeholder="slug_permiso" required></div>
                <div class="col-md-2"><input name="module" class="form-control" placeholder="Módulo" required></div>
                <div class="col-md-3"><input name="description" class="form-control" placeholder="Descripción"></div>
                <div class="col-md-1"><button class="btn btn-dark w-100">Crear</button></div>
            </form>
        </div>
    </div>
    <div class="row g-3">
        @foreach ($roles as $role)
            <div class="col-lg-6">
                <form method="POST" action="{{ route('admin.security.roles.permissions', $role) }}" class="card border-0 shadow-sm h-100">
                    @csrf @method('PUT')
                    <div class="card-header bg-white fw-semibold">{{ $role->name }}</div>
                    <div class="card-body">
                        @foreach ($permissions as $permission)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" @checked($role->permissions->contains($permission))>
                                <label class="form-check-label">{{ $permission->name }} <span class="text-secondary small">({{ $permission->module }})</span></label>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer bg-white"><button class="btn btn-dark btn-sm">Guardar permisos</button></div>
                </form>
            </div>
        @endforeach
    </div>
@endsection
