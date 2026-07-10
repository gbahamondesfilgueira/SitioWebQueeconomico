@extends('layouts.admin')
@section('title', 'Editar variante')
@section('page-title', 'Editar variante')
@section('content')
    <form method="POST" action="{{ route('admin.products.variants.update', [$product, $variant]) }}" enctype="multipart/form-data">@csrf @method('PUT') @include('admin.products.variants.partials.form')</form>
@endsection
