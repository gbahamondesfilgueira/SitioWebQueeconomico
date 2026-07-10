@extends('layouts.admin')
@section('title', 'Crear moneda')
@section('page-title', 'Crear moneda')
@section('content')
    <form method="POST" action="{{ route('admin.currencies.store') }}" class="card border-0 shadow-sm"><div class="card-body">@include('admin.currencies.form')</div></form>
@endsection
