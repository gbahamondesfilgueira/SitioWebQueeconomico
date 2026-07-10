@extends('layouts.admin')
@section('title','Logs de integración')
@section('content')
<h1 class="h4 mb-3">Logs de integración</h1>
<div class="card"><div class="card-body table-responsive"><table class="table"><thead><tr><th>Integración</th><th>Evento</th><th>Estado</th><th>Endpoint</th><th>Fecha</th><th></th></tr></thead><tbody>@foreach($logs as $log)<tr><td>{{ $log->integration?->name }}</td><td>{{ $log->event_type }}</td><td>{{ $log->status }}</td><td>{{ $log->endpoint }}</td><td>{{ $log->created_at }}</td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.integration-logs.show',$log) }}">Ver</a></td></tr>@endforeach</tbody></table>{{ $logs->links() }}</div></div>
@endsection
