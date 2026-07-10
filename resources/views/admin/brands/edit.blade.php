@extends('layouts.admin')

@section('title', 'Editar marca')
@section('page-title', 'Editar marca')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.brands.update', $brand) }}" enctype="multipart/form-data" class="row g-3">
                @csrf
                @method('PUT')
                @include('admin.brands.partials.form')
            </form>
        </div>
    </div>
@endsection
