@extends('layouts.account')
@section('title', 'Mi Lista de Deseos')
@section('content')
    <div class="card border-0 shadow-sm"><div class="card-header bg-white fw-semibold">Mi Lista de Deseos</div><div class="list-group list-group-flush">@forelse($customer->wishlistItems as $item)<div class="list-group-item d-flex justify-content-between"><span>{{ $item->product?->name }} {{ $item->variant?->name }}</span><span class="text-secondary">{{ $item->notes }}</span></div>@empty<div class="list-group-item text-secondary">Sin productos en lista de deseos.</div>@endforelse</div></div>
@endsection
