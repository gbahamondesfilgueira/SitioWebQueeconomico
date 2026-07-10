@extends('layouts.admin')
@section('title','Editar pack')
@section('page-title','Editar pack')
@section('content')<form method="POST" action="{{ route('admin.product-packs.update',$pack) }}" enctype="multipart/form-data">@csrf @method('PUT') @include('admin.product_packs.partials.form')</form>@endsection
