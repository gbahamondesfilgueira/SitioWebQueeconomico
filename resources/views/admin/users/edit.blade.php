@extends('layouts.admin')

@section('title', 'Editar usuario')
@section('page-title', 'Editar usuario')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label" for="name">Nombre</label>
                    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="phone">Teléfono</label>
                    <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="role_id">Rol</label>
                    <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="password">Nueva password</label>
                    <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="password_confirmation">Confirmar nueva password</label>
                    <input class="form-control" id="password_confirmation" name="password_confirmation" type="password">
                </div>

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', $user->is_active))>
                        <label class="form-check-label" for="is_active">Usuario activo</label>
                        @error('is_active') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <a class="btn btn-outline-secondary" href="{{ route('admin.users.index') }}">Cancelar</a>
                    <button class="btn btn-primary" type="submit">Actualizar usuario</button>
                </div>
            </form>
        </div>
    </div>
@endsection
