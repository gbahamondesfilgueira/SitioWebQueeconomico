@extends('layouts.admin')

@section('title', 'Auditoría')
@section('page-title', 'Auditoría')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold">Registros de auditoría</div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Módulo</th>
                        <th>Descripción</th>
                        <th>IP</th>
                        <th>User Agent</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($auditLogs as $log)
                        <tr>
                            <td class="small text-secondary">{{ $log->created_at?->format('d/m/Y H:i:s') }}</td>
                            <td>{{ $log->user?->name ?? 'Sistema' }}</td>
                            <td><span class="badge text-bg-light">{{ $log->action }}</span></td>
                            <td>{{ $log->module }}</td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->ip_address }}</td>
                            <td class="small text-secondary text-truncate" style="max-width: 260px;">{{ $log->user_agent }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-secondary py-4">No hay registros de auditoría.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($auditLogs->hasPages())
            <div class="card-footer bg-white">{{ $auditLogs->links() }}</div>
        @endif
    </div>
@endsection
