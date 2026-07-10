@extends('layouts.admin')
@section('title','Crear bodega')
@section('page-title','Crear bodega')
@section('content')<div class="card border-0 shadow-sm"><div class="card-body"><form method="POST" action="{{ route('admin.warehouses.store') }}" class="row g-3">@csrf @include('admin.warehouses.partials.form')</form></div></div>@endsection
