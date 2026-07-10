@extends('layouts.admin')

@section('title', 'Crear marca')
@section('page-title', 'Crear marca')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.brands.store') }}" enctype="multipart/form-data" class="row g-3">
                @csrf
                @include('admin.brands.partials.form')
            </form>
        </div>
    </div>
@endsection
