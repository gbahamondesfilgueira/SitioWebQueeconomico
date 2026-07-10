@extends('layouts.admin')
@section('title','Importar tarifas')
@section('page-title','Importar tarifas')
@section('content')
    <div class="card border-0 shadow-sm"><div class="card-header bg-white d-flex justify-content-between"><span class="fw-semibold">Importaciones</span><a href="{{ route('admin.shipping.imports.create') }}" class="btn btn-dark btn-sm">Subir archivo</a></div><table class="table mb-0"><thead><tr><th>ID</th><th>Transportista</th><th>Estado</th><th>Filas</th><th></th></tr></thead><tbody>@foreach($imports as $import)<tr><td>{{ $import->id }}</td><td>{{ $import->carrier?->name }}</td><td>{{ $import->status }}</td><td>{{ $import->successful_rows }}/{{ $import->total_rows }}</td><td><a href="{{ route('admin.shipping.imports.show',$import) }}" class="btn btn-sm btn-outline-dark">Ver</a></td></tr>@endforeach</tbody></table></div>
@endsection
