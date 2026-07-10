@extends('layouts.admin')
@section('title','Crear pack')
@section('page-title','Crear pack')
@section('content')<form method="POST" action="{{ route('admin.product-packs.store') }}" enctype="multipart/form-data">@csrf @include('admin.product_packs.partials.form')</form>@endsection
