@extends('layouts.admin')
@section('title','Detalle integración')
@section('content')
<div class="d-flex justify-content-between mb-3"><h1 class="h4">{{ $integration->name }}</h1><a class="btn btn-outline-primary" href="{{ route('admin.integrations.edit',$integration) }}">Editar</a></div>
<div class="row g-3 mb-3">
<div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Estado</div><div class="h4">{{ $integration->status }}</div></div></div></div>
<div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Mapeos</div><div class="h4">{{ $integration->product_mappings_count }}</div></div></div></div>
<div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Jobs</div><div class="h4">{{ $integration->sync_jobs_count }}</div></div></div></div>
<div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Webhooks</div><div class="h4">{{ $integration->webhook_events_count }}</div></div></div></div>
</div>
<div class="card mb-3"><div class="card-body"><h2 class="h5">Credenciales enmascaradas</h2><pre>{{ json_encode($masked, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre></div></div>
<form class="d-inline" method="POST" action="{{ route('admin.integrations.test',$integration) }}">@csrf<button class="btn btn-secondary">Probar conexión</button></form>
<form class="d-inline" method="POST" action="{{ route('admin.integrations.sync-stock',$integration) }}">@csrf<button class="btn btn-outline-secondary">Sync stock</button></form>
<form class="d-inline" method="POST" action="{{ route('admin.integrations.sync-prices',$integration) }}">@csrf<button class="btn btn-outline-secondary">Sync precios</button></form>
<form class="d-inline" method="POST" action="{{ route('admin.integrations.import-orders',$integration) }}">@csrf<button class="btn btn-outline-secondary">Importar pedidos</button></form>
<div class="card mt-3"><div class="card-body"><h2 class="h5">Logs recientes</h2><table class="table"><thead><tr><th>Evento</th><th>Estado</th><th>Fecha</th></tr></thead><tbody>@foreach($recentLogs as $log)<tr><td>{{ $log->event_type }}</td><td>{{ $log->status }}</td><td>{{ $log->created_at }}</td></tr>@endforeach</tbody></table></div></div>
@endsection
