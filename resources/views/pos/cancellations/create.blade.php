@extends('layouts.pos')
@section('title', 'Anular venta POS')
@section('content')
<div class="row justify-content-center"><div class="col-lg-6">
<form method="POST" action="{{ route('pos.sales.cancel.store', $order) }}" class="bg-white border rounded p-4">
@csrf
<h1 class="h4 mb-3">Anular venta {{ $order->order_number }}</h1>
<div class="alert alert-warning">Esta acción marcará la venta como anulada y registrará movimiento de caja.</div>
<label class="form-label">Motivo</label><input name="reason" class="form-control mb-3" required>
<label class="form-label">Notas</label><textarea name="notes" class="form-control mb-3"></textarea>
<label class="form-check mb-3"><input type="checkbox" name="restore_stock" value="1" class="form-check-input" checked> Restaurar stock</label>
<button class="btn btn-danger w-100">Anular venta</button>
</form>
</div></div>
@endsection
