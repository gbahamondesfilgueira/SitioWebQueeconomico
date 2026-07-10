@extends('layouts.admin')
@section('title', 'Cancelar pedido')
@section('page-title', 'Cancelar '.$order->order_number)
@section('content')
    <form method="POST" action="{{ route('admin.orders.cancel.store', $order) }}" class="card border-0 shadow-sm">@csrf<div class="card-body"><label class="form-label">Motivo</label><input name="reason" class="form-control mb-3" required><label class="form-label">Notas</label><textarea name="notes" class="form-control mb-3"></textarea><label class="form-check"><input type="checkbox" name="restore_stock" value="1" class="form-check-input" checked> Restaurar stock</label></div><div class="card-footer bg-white text-end"><button class="btn btn-danger">Cancelar pedido</button></div></form>
@endsection
