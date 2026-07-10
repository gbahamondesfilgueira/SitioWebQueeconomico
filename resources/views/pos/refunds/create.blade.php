@extends('layouts.pos')
@section('title', 'Devolución POS')
@section('content')
<div class="row justify-content-center"><div class="col-lg-6">
<form method="POST" action="{{ route('pos.sales.refund.store', $order) }}" class="bg-white border rounded p-4">
@csrf
<h1 class="h4 mb-3">Devolución venta {{ $order->order_number }}</h1>
<p class="text-muted">Total pagado: ${{ number_format($order->payments->sum('amount'), 0, ',', '.') }}</p>
<label class="form-label">Monto a devolver</label><input name="refund_amount" type="number" min="1" max="{{ (int) $order->payments->sum('amount') }}" step="1" class="form-control mb-3" required>
<label class="form-label">Método de devolución</label><select name="refund_method" class="form-select mb-3">@foreach($methods as $method)<option value="{{ $method->code }}">{{ $method->name }}</option>@endforeach</select>
<label class="form-label">Motivo</label><input name="reason" class="form-control mb-3" required>
<label class="form-label">Notas</label><textarea name="notes" class="form-control mb-3"></textarea>
<label class="form-check mb-3"><input type="checkbox" name="restore_stock" value="1" class="form-check-input"> Restaurar stock</label>
<button class="btn btn-warning w-100">Registrar devolución</button>
</form>
</div></div>
@endsection
