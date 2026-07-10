@extends('layouts.admin')
@section('title', 'Editar moneda')
@section('page-title', 'Editar moneda')
@section('content')
    <form method="POST" action="{{ route('admin.currencies.update', $currency) }}" class="card border-0 shadow-sm">@method('PUT')<div class="card-body">@include('admin.currencies.form')</div></form>
@endsection
