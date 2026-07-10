@extends('layouts.admin')
@section('title','API Client')
@section('content')
<h1 class="h4">{{ $client->name }}</h1>
@if($plainToken)<div class="alert alert-warning"><strong>Token visible solo una vez:</strong><br><code>{{ $plainToken }}</code></div>@endif
<div class="card"><div class="card-body"><p><strong>Código:</strong> {{ $client->code }}</p><p><strong>Activo:</strong> {{ $client->is_active ? 'Sí' : 'No' }}</p><p><strong>Permisos:</strong> {{ implode(', ', $client->permissions ?? []) }}</p><form method="POST" action="{{ route('admin.api-clients.revoke',$client) }}">@csrf @method('PATCH')<button class="btn btn-danger">Revocar</button></form></div></div>
@endsection
