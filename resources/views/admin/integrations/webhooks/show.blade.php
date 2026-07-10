@extends('layouts.admin')
@section('title','Detalle webhook')
@section('content')<h1 class="h4">Webhook #{{ $event->id }}</h1><div class="card"><div class="card-body"><p><strong>Estado:</strong> {{ $event->status }}</p><p><strong>Error:</strong> {{ $event->error_message }}</p><pre>{{ json_encode($event->payload, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre></div></div>@endsection
