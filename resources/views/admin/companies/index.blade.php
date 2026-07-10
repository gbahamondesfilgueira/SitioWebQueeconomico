@extends('layouts.admin')
@section('title', 'Empresas')
@section('page-title', 'Empresas')
@section('content')
    <div class="d-flex justify-content-end mb-3"><a href="{{ route('admin.companies.create') }}" class="btn btn-dark">Crear empresa</a></div>
    <x-admin.report-table :columns="['Nombre','RUT','Email','Moneda','IVA','Estado','Editar']" :rows="$companies->map(fn($c) => [$c->name,$c->rut ?: '-',$c->email ?: '-',$c->currency,$c->tax_percentage.'%',$c->is_active ? 'Activa' : 'Inactiva', route('admin.companies.edit', $c)])" :paginator="$companies" />
@endsection
