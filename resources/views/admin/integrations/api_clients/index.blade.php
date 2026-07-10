@extends('layouts.admin')
@section('title','API Clients')
@section('content')
<div class="d-flex justify-content-between mb-3"><h1 class="h4">API Clients</h1><a class="btn btn-primary" href="{{ route('admin.api-clients.create') }}">Nuevo API Client</a></div>
<div class="card"><div class="card-body table-responsive"><table class="table"><thead><tr><th>Nombre</th><th>Código</th><th>Activo</th><th>Último uso</th><th></th></tr></thead><tbody>@foreach($clients as $client)<tr><td>{{ $client->name }}</td><td>{{ $client->code }}</td><td>{{ $client->is_active ? 'Sí' : 'No' }}</td><td>{{ $client->last_used_at }}</td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.api-clients.show',$client) }}">Ver</a></td></tr>@endforeach</tbody></table>{{ $clients->links() }}</div></div>
@endsection
