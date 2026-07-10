@extends('layouts.pos')
@section('title', 'Ingreso de caja')
@section('content')
<div class="row justify-content-center"><div class="col-lg-6"><form method="POST" action="{{ route('pos.cash.income.store') }}" class="bg-white border rounded p-4">
@csrf
<h1 class="h4 mb-3">Registrar ingreso</h1>
<label class="form-label">Monto</label><input name="amount" type="number" min="1" step="1" class="form-control mb-3" required>
<label class="form-label">Medio de pago</label><select name="payment_method_id" class="form-select mb-3"><option value="">Sin medio</option>@foreach($methods as $method)<option value="{{ $method->id }}">{{ $method->name }}</option>@endforeach</select>
<label class="form-label">Descripción</label><input name="description" class="form-control mb-3" required>
<button class="btn btn-success w-100">Guardar ingreso</button>
</form></div></div>
@endsection
