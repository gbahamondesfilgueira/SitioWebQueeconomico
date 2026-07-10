@extends('layouts.admin')
@section('title','Detalle mapeo pedido')
@section('content')<h1 class="h4">Pedido externo {{ $mapping->external_order_id }}</h1><div class="card"><div class="card-body"><pre>{{ json_encode($mapping->toArray(), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre></div></div>@endsection
