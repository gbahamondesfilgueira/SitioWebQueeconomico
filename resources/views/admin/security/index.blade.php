@extends('layouts.admin')
@section('title', 'Seguridad')
@section('page-title', 'Seguridad')
@section('content')
    <div class="row g-3">
        <x-admin.report-card label="Headers de seguridad" value="Activos" />
        <x-admin.report-card label="CSRF" value="Activo" />
        <x-admin.report-card label="API" value="Token + rate limit" />
        <x-admin.report-card label="Backups públicos" value="No expuestos" />
    </div>
@endsection
