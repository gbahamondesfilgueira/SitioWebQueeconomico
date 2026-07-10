@extends('layouts.admin')
@section('title', 'Crear variante')
@section('page-title', 'Crear variante')
@section('content')
    <form method="POST" action="{{ route('admin.products.variants.store', $product) }}" enctype="multipart/form-data">@csrf @include('admin.products.variants.partials.form')</form>
@endsection
