@extends('layouts.admin')

@section('title', 'Importaciones masivas')
@section('page-title', 'Importaciones masivas')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Importaciones masivas</h1>
            <p class="text-secondary mb-0">Carga productos, categorías, marcas, etiquetas, atributos y variantes desde CSV WooCommerce.</p>
        </div>
        <a href="{{ route('admin.imports.products.create') }}" class="btn btn-dark">Subir CSV</a>
    </div>

    <x-admin.report-table
        :columns="['Fecha','Tipo','Estado','Filas','Correctas','Fallidas','Usuario','Ver']"
        :rows="$imports->map(fn($import) => [
            $import->created_at?->format('d/m/Y H:i'),
            $import->import_type,
            $import->status,
            $import->total_rows,
            $import->successful_rows,
            $import->failed_rows,
            $import->creator?->name ?? '-',
            route('admin.imports.products.show', $import),
        ])"
        :paginator="$imports"
    />
@endsection
