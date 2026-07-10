@extends('layouts.admin')
@section('title','Editar ubicación')
@section('page-title','Editar ubicación')
@section('content')<div class="card border-0 shadow-sm"><div class="card-body"><form method="POST" action="{{ route('admin.warehouses.locations.update',[$warehouse,$location]) }}" class="row g-3">@csrf @method('PUT') @include('admin.warehouses.locations.partials.form')</form></div></div>@endsection
