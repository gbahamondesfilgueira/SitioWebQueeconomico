@extends('layouts.admin')
@section('title','Editar mapeo')
@section('content')<h1 class="h4 mb-3">Editar mapeo</h1>@include('admin.integrations.product_mappings.form',['action'=>route('admin.external-product-mappings.update',$mapping),'method'=>'PUT'])@endsection
