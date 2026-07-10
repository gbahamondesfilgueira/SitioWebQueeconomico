@extends('layouts.admin')
@section('title','Editar bodega')
@section('page-title','Editar bodega')
@section('content')<div class="card border-0 shadow-sm"><div class="card-body"><form method="POST" action="{{ route('admin.warehouses.update',$warehouse) }}" class="row g-3">@csrf @method('PUT') @include('admin.warehouses.partials.form')</form></div></div>@endsection
