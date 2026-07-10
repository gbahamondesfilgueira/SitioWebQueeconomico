@extends('layouts.admin')
@section('title', 'Monedas')
@section('page-title', 'Monedas')
@section('content')
    <div class="d-flex justify-content-end mb-3"><a href="{{ route('admin.currencies.create') }}" class="btn btn-dark">Crear moneda</a></div>
    <x-admin.report-table :columns="['Nombre','Código','Símbolo','Decimales','Default','Estado','Editar']" :rows="$currencies->map(fn($c) => [$c->name,$c->code,$c->symbol,$c->decimal_places,$c->is_default ? 'Sí' : 'No',$c->is_active ? 'Activa' : 'Inactiva', route('admin.currencies.edit', $c)])" :paginator="$currencies" />
@endsection
