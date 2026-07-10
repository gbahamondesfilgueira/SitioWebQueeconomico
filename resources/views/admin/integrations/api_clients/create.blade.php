@extends('layouts.admin')
@section('title','Crear API Client')
@section('content')
<form method="POST" action="{{ route('admin.api-clients.store') }}" class="card">@csrf
<div class="card-body row g-3">
<div class="col-md-6"><label class="form-label">Nombre</label><input name="name" class="form-control" required></div>
<div class="col-md-6"><label class="form-label">Código</label><input name="code" class="form-control" required></div>
<div class="col-12"><label class="form-label">Descripción</label><textarea name="description" class="form-control"></textarea></div>
<div class="col-md-4"><label class="form-label">Rate limit/min</label><input name="rate_limit_per_minute" type="number" value="60" class="form-control"></div>
<div class="col-12"><label class="form-label">Permisos</label><select name="permissions[]" class="form-select" multiple>@foreach(['read_products','write_products','read_stock','write_stock','read_orders','write_orders','read_customers','write_customers','read_shipments','write_shipments'] as $perm)<option value="{{ $perm }}">{{ $perm }}</option>@endforeach</select></div>
</div><div class="card-footer text-end"><button class="btn btn-primary">Crear</button></div></form>
@endsection
