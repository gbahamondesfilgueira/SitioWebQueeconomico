@extends('layouts.admin')

@section('title', 'Crear producto')
@section('page-title', 'Crear producto')

@section('content')
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.products.partials.form')
    </form>
@endsection
