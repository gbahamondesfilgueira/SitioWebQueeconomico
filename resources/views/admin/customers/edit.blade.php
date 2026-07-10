@extends('layouts.admin')

@section('title', 'Editar cliente')
@section('page-title', 'Editar cliente')

@section('content')
    <form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="card border-0 shadow-sm">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.customers.partials.form')
        </div>
        <div class="card-footer bg-white d-flex justify-content-end gap-2">
            <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-outline-secondary">Cancelar</a>
            <button class="btn btn-dark">Actualizar cliente</button>
        </div>
    </form>
@endsection
