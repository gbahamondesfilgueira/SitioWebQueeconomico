@extends('layouts.account')
@section('title', 'Mis Puntos')
@section('content')
    <div class="card border-0 shadow-sm"><div class="card-header bg-white fw-semibold">Mis Puntos: {{ number_format((float) $customer->reward_points, 0, ',', '.') }}</div><div class="list-group list-group-flush">@forelse($customer->rewardTransactions as $tx)<div class="list-group-item">{{ $tx->created_at?->format('d/m/Y H:i') }} · {{ $tx->type }} · {{ $tx->points }} · {{ $tx->description }}</div>@empty<div class="list-group-item text-secondary">Sin movimientos de puntos.</div>@endforelse</div></div>
@endsection
