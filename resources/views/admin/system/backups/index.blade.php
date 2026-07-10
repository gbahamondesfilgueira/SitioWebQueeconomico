@extends('layouts.admin')
@section('title', 'Backups')
@section('page-title', 'Backups')
@section('content')
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.system.backups.store') }}" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Tipo de backup</label>
                    <select name="backup_type" class="form-select">
                        <option value="database">Base de datos</option>
                        <option value="files">Archivos</option>
                        <option value="full">Completo</option>
                    </select>
                </div>
                <div class="col-md-3"><button class="btn btn-dark w-100">Crear backup</button></div>
            </form>
        </div>
    </div>
    <x-admin.report-table :columns="['Tipo','Estado','Archivo','Tamaño','Inicio','Fin','Ver']" :rows="$backups->map(fn($b) => [$b->backup_type,$b->status,$b->file_path ?: '-', $b->file_size ? number_format($b->file_size / 1024, 1, ',', '.').' KB' : '-', $b->started_at?->format('d/m/Y H:i') ?: '-', $b->finished_at?->format('d/m/Y H:i') ?: '-', route('admin.system.backups.show', $b)])" :paginator="$backups" />
@endsection
