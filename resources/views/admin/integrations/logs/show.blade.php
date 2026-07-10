@extends('layouts.admin')
@section('title','Detalle log')
@section('content')
<h1 class="h4">Log #{{ $log->id }}</h1>
<div class="card"><div class="card-body"><p><strong>Evento:</strong> {{ $log->event_type }}</p><p><strong>Estado:</strong> {{ $log->status }}</p><p><strong>Error:</strong> {{ $log->error_message }}</p><h2 class="h6">Request</h2><pre>{{ json_encode($log->request_payload, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre><h2 class="h6">Response</h2><pre>{{ json_encode($log->response_payload, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre></div></div>
@endsection
