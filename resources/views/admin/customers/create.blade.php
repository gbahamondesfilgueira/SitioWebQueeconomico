@extends('layouts.admin')

@section('title', 'Crear cliente')
@section('page-title', 'Crear cliente')

@section('content')
    <form method="POST" action="{{ route('admin.customers.store') }}" class="card border-0 shadow-sm">
        @csrf
        <div class="card-body">
            @include('admin.customers.partials.form')
        </div>
        <div class="card-footer bg-white d-flex justify-content-end gap-2">
            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button class="btn btn-dark">Guardar cliente</button>
        </div>
    </form>
@endsection
