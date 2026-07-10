@extends('layouts.admin')

@section('title', 'Editar categoría')
@section('page-title', 'Editar categoría')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data" class="row g-3">
                @csrf
                @method('PUT')
                @include('admin.categories.partials.form')
            </form>
        </div>
    </div>
@endsection
