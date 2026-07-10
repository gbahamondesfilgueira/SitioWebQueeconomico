@extends('layouts.account')
@section('title', 'Mis Favoritos')
@section('content')
    <div class="card border-0 shadow-sm"><div class="card-header bg-white fw-semibold">Mis Favoritos</div><div class="list-group list-group-flush">@forelse($customer->favorites as $favorite)<div class="list-group-item">{{ $favorite->product?->name }} {{ $favorite->variant?->name }}</div>@empty<div class="list-group-item text-secondary">Sin favoritos todavía.</div>@endforelse</div></div>
@endsection
