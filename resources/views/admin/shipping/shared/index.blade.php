@extends('layouts.admin')
@section('title', $title)
@section('page-title', $title)
@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between"><span class="fw-semibold">{{ $title }}</span><a href="{{ route('admin.shipping.'.$route.'.create') }}" class="btn btn-dark btn-sm">Crear</a></div>
        <div class="table-responsive"><table class="table table-hover mb-0"><thead><tr>@foreach($columns as $label)<th>{{ $label }}</th>@endforeach<th></th></tr></thead><tbody>
            @forelse($items as $item)<tr>@foreach($columns as $key => $label)<td>@php($value = data_get($item, $key)) @if($key === 'is_active')<span class="badge {{ $value ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $value ? 'Activo' : 'Inactivo' }}</span>@else{{ $value }}@endif</td>@endforeach<td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.shipping.'.$route.'.edit', $item) }}">Editar</a><form class="d-inline" method="POST" action="{{ route('admin.shipping.'.$route.'.toggle', $item) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-warning">Estado</button></form></td></tr>@empty<tr><td colspan="{{ count($columns)+1 }}" class="text-center text-secondary py-4">Sin registros.</td></tr>@endforelse
        </tbody></table></div><div class="card-footer bg-white">{{ $items->links() }}</div>
    </div>
@endsection
