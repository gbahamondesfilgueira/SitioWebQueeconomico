@extends('layouts.admin')
@section('title', 'Sucursales')
@section('page-title', 'Sucursales')
@section('content')
    <div class="d-flex justify-content-end mb-3"><a href="{{ route('admin.branches.create') }}" class="btn btn-dark">Crear sucursal</a></div>
    <x-admin.report-table :columns="['Nombre','Código','Empresa','Región','Comuna','Estado','Editar']" :rows="$branches->map(fn($b) => [$b->name,$b->code,$b->company?->name ?? '-',$b->region ?: '-',$b->commune ?: '-',$b->is_active ? 'Activa' : 'Inactiva', route('admin.branches.edit', $b)])" :paginator="$branches" />
@endsection
