@extends('layouts.admin')
@section('title', 'Logs del sistema')
@section('page-title', 'Logs del sistema')
@section('content')
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Laravel log</div>
                <pre class="card-body small mb-0" style="max-height: 520px; overflow:auto;">{{ $laravelLogs->implode("\n") ?: 'Sin logs disponibles.' }}</pre>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Integraciones fallidas</div>
                <div class="list-group list-group-flush">
                    @forelse ($integrationLogs as $log)
                        <div class="list-group-item small">{{ $log->integration?->name ?? '-' }}: {{ $log->error_message ?: $log->event_type }}</div>
                    @empty
                        <div class="list-group-item text-secondary">Sin errores.</div>
                    @endforelse
                </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Auditoría reciente</div>
                <div class="list-group list-group-flush">
                    @foreach ($auditLogs as $log)
                        <div class="list-group-item small">{{ $log->created_at?->format('d/m/Y H:i') }} - {{ $log->module }} - {{ $log->action }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
