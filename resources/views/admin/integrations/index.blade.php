@extends('layouts.admin')
@section('title', 'Integraciones')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="h4">Integraciones</h1><a class="btn btn-primary" href="{{ route('admin.integrations.create') }}">Nueva integración</a></div>
<div class="row g-3 mb-3"><div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Activas</div><div class="h3">{{ $activeCount }}</div></div></div></div><div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Con error</div><div class="h3">{{ $errorCount }}</div></div></div></div></div>
<div class="card"><div class="card-body table-responsive"><table class="table align-middle"><thead><tr><th>Nombre</th><th>Tipo</th><th>Ambiente</th><th>Estado</th><th>Logs</th><th>Mapeos</th><th></th></tr></thead><tbody>
@foreach($integrations as $integration)<tr><td>{{ $integration->name }}<br><small class="text-muted">{{ $integration->code }}</small></td><td>{{ $integration->provider_type }}</td><td>{{ $integration->environment }}</td><td>{{ $integration->status }}</td><td>{{ $integration->logs_count }}</td><td>{{ $integration->product_mappings_count }}</td><td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.integrations.show', $integration) }}">Ver</a></td></tr>@endforeach
</tbody></table>{{ $integrations->links() }}</div></div>
@endsection
