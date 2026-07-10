@extends('layouts.admin')
@section('title', 'Editar sucursal')
@section('page-title', 'Editar sucursal')
@section('content')
    <form method="POST" action="{{ route('admin.branches.update', $branch) }}" class="card border-0 shadow-sm">@method('PUT')<div class="card-body">@include('admin.branches.form')</div></form>
@endsection
