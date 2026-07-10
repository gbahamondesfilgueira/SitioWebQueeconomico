@extends('layouts.admin')
@section('title', 'Editar empresa')
@section('page-title', 'Editar empresa')
@section('content')
    <form method="POST" action="{{ route('admin.companies.update', $company) }}" class="card border-0 shadow-sm">@method('PUT')<div class="card-body">@include('admin.companies.form')</div></form>
@endsection
