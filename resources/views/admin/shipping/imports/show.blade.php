@extends('layouts.admin')
@section('title','Importación')
@section('page-title','Importación #'.$import->id)
@section('content')
    <div class="card border-0 shadow-sm"><div class="card-body"><p>Estado: {{ $import->status }}</p><p>Filas: {{ $import->successful_rows }} correctas, {{ $import->failed_rows }} fallidas.</p>@if($import->error_report_path)<a href="{{ route('admin.shipping.imports.errors', $import) }}" class="btn btn-outline-danger">Reporte errores</a>@endif</div></div>
@endsection
