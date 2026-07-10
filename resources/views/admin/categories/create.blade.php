@extends('layouts.admin')

@section('title', 'Crear categoría')
@section('page-title', 'Crear categoría')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="row g-3">
                @csrf
                @include('admin.categories.partials.form')
            </form>
        </div>
    </div>
@endsection
