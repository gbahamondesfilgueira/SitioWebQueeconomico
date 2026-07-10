@extends('layouts.account')
@section('title', 'Mis Documentos')
@section('content')
    <div class="card border-0 shadow-sm"><div class="card-header bg-white fw-semibold">Mis Documentos</div><div class="list-group list-group-flush">@forelse($customer->documents as $document)<div class="list-group-item">{{ $document->document_type }} · {{ $document->description }}</div>@empty<div class="list-group-item text-secondary">Sin documentos cargados.</div>@endforelse</div></div>
@endsection
