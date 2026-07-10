@extends('layouts.admin')
@section('title', 'Devolución')
@section('page-title', 'Devolución '.$return->return_number)
@section('content')
    <div class="card border-0 shadow-sm"><div class="card-body"><p>Pedido: {{ $return->order?->order_number }} · Estado: {{ $return->status }}</p>@foreach($return->items as $item)<div class="border-top py-2">{{ $item->orderItem?->product_name }} · {{ $item->quantity }} · {{ $item->condition }} · {{ $item->restock ? 'Restock' : 'Sin restock' }}</div>@endforeach<form method="POST" action="{{ route('admin.order-returns.approve', $return) }}" class="d-inline">@csrf @method('PATCH')<button class="btn btn-outline-primary mt-3">Aprobar</button></form><form method="POST" action="{{ route('admin.order-returns.receive', $return) }}" class="d-inline">@csrf @method('PATCH')<button class="btn btn-outline-success mt-3">Recibir</button></form></div></div>
@endsection
