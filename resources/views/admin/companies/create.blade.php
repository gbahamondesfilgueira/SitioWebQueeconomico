@extends('layouts.admin')
@section('title', 'Crear empresa')
@section('page-title', 'Crear empresa')
@section('content')
    <form method="POST" action="{{ route('admin.companies.store') }}" class="card border-0 shadow-sm"><div class="card-body">@include('admin.companies.form')</div></form>
@endsection
