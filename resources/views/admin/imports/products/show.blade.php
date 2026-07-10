@extends('layouts.admin')

@section('title', 'Resultado importación')
@section('page-title', 'Resultado importación')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Resultado importación #{{ $import->id }}</h1>
            <p class="text-secondary mb-0">{{ $import->file_path }}</p>
        </div>
        <a href="{{ route('admin.imports.products.create') }}" class="btn btn-dark">Subir otro CSV</a>
    </div>

    <div class="row g-3 mb-4">
        <x-admin.report-card label="Estado" :value="$import->status" />
        <x-admin.report-card label="Filas" :value="$import->total_rows" />
        <x-admin.report-card label="Correctas" :value="$import->successful_rows" />
        <x-admin.report-card label="Fallidas" :value="$import->failed_rows" />
    </div>

    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Resumen</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        @foreach (($import->summary ?? []) as $key => $value)
                            <dt class="col-8">{{ str_replace('_', ' ', $key) }}</dt>
                            <dd class="col-4 text-end">{{ $value }}</dd>
                        @endforeach
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Errores</div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>Fila</th><th>Producto</th><th>Error</th></tr></thead>
                        <tbody>
                            @forelse (($import->errors ?? []) as $error)
                                <tr>
                                    <td>{{ $error['row'] ?? '-' }}</td>
                                    <td>{{ $error['name'] ?? '-' }}</td>
                                    <td>{{ $error['message'] ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-secondary text-center py-4">Sin errores registrados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
