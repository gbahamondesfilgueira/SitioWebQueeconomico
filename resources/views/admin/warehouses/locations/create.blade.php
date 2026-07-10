@extends('layouts.admin')
@section('title','Crear ubicación')
@section('page-title','Crear ubicación')
@section('content')<div class="card border-0 shadow-sm"><div class="card-body"><form method="POST" action="{{ route('admin.warehouses.locations.store',$warehouse) }}" class="row g-3">@csrf @include('admin.warehouses.locations.partials.form')</form></div></div>@endsection
