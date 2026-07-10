@extends('layouts.admin')
@section('title', 'Estado del sistema')
@section('page-title', 'Estado del sistema')
@section('content')
    <div class="row g-3 mb-4">
        @foreach ($checks as $check)
            <x-admin.report-card :label="$check['check_name']" :value="strtoupper($check['status']).' - '.$check['message']" />
        @endforeach
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h2 class="h5">Últimos errores</h2>
            <pre class="bg-light p-3 small mb-0" style="max-height: 360px; overflow:auto;">{{ implode("\n", $latest['errors']) ?: 'Sin errores recientes.' }}</pre>
            <p class="text-secondary mt-3 mb-0">Uso aproximado de storage: {{ number_format(($latest['storage_usage'] ?? 0) / 1024 / 1024, 2, ',', '.') }} MB</p>
        </div>
    </div>
@endsection
