@extends('layouts.admin')
@section('title','Subir tarifas')
@section('page-title','Subir tarifas')
@section('content')
    <form method="POST" action="{{ route('admin.shipping.imports.store') }}" enctype="multipart/form-data" class="card border-0 shadow-sm">@csrf<div class="card-body row g-3"><div class="col-md-4"><label class="form-label">Transportista</label><select name="shipping_carrier_id" class="form-select">@foreach($carriers as $carrier)<option value="{{ $carrier->id }}">{{ $carrier->name }}</option>@endforeach</select></div><div class="col-md-8"><label class="form-label">CSV/XLSX</label><input type="file" name="file" class="form-control" required></div></div><div class="card-footer bg-white text-end"><button class="btn btn-dark">Subir</button></div></form>
@endsection
