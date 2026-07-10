@extends('layouts.admin')
@section('title', 'Detalle backup')
@section('page-title', 'Detalle backup')
@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Tipo</dt><dd class="col-sm-9">{{ $backup->backup_type }}</dd>
                <dt class="col-sm-3">Estado</dt><dd class="col-sm-9">{{ $backup->status }}</dd>
                <dt class="col-sm-3">Archivo</dt><dd class="col-sm-9">{{ $backup->file_path ?: '-' }}</dd>
                <dt class="col-sm-3">Válido</dt><dd class="col-sm-9">{{ $isValid ? 'Sí' : 'No' }}</dd>
                <dt class="col-sm-3">Error</dt><dd class="col-sm-9">{{ $backup->error_message ?: '-' }}</dd>
            </dl>
        </div>
    </div>
@endsection
