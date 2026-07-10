@extends('layouts.admin')
@section('title', 'Seguridad usuarios')
@section('page-title', 'Seguridad usuarios')
@section('content')
    <x-admin.report-table :columns="['Usuario','Email','Rol','Activo','Último login','IP','Fallidos','Bloqueado hasta']" :rows="$users->map(fn($u) => [$u->name,$u->email,$u->role?->name ?? '-', $u->is_active ? 'Sí' : 'No', $u->last_login_at?->format('d/m/Y H:i') ?: '-', $u->last_login_ip ?: '-', $u->failed_login_attempts, $u->locked_until?->format('d/m/Y H:i') ?: '-'])" :paginator="$users" />
@endsection
