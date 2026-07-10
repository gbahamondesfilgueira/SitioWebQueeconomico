@extends('layouts.admin')
@section('title','Crear mapeo')
@section('content')<h1 class="h4 mb-3">Crear mapeo de producto</h1>@include('admin.integrations.product_mappings.form',['action'=>route('admin.external-product-mappings.store'),'method'=>'POST','mapping'=>new \App\Models\ExternalProductMapping()])@endsection
