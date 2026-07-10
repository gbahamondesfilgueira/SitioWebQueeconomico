@extends('layouts.admin')
@section('title', 'Crear sucursal')
@section('page-title', 'Crear sucursal')
@section('content')
    <form method="POST" action="{{ route('admin.branches.store') }}" class="card border-0 shadow-sm"><div class="card-body">@include('admin.branches.form')</div></form>
@endsection
