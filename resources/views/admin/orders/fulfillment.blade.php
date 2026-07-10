@extends('layouts.admin')
@section('title', 'Preparación pedido')
@section('page-title', 'Preparación '.$order->order_number)
@section('content')
    <div class="card border-0 shadow-sm"><div class="card-body"><form method="POST" action="{{ route('admin.orders.fulfillment.update', $order) }}" class="row g-2 mb-3">@csrf @method('PATCH')<div class="col-md-4"><select name="fulfillment_status" class="form-select">@foreach(['pending','picking','packed','ready','shipped','delivered','cancelled'] as $s)<option @selected($order->fulfillment_status===$s)>{{ $s }}</option>@endforeach</select></div><div class="col-md-6"><input name="notes" class="form-control" placeholder="Notas"></div><div class="col-md-2"><button class="btn btn-dark w-100">Guardar</button></div></form>@foreach($order->fulfillment?->items ?? [] as $item)<div class="border-top py-2">{{ $item->orderItem?->product_name }} · requerido {{ $item->required_quantity }} · {{ $item->status }}</div>@endforeach</div></div>
@endsection
