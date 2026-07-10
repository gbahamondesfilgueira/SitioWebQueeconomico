@extends('layouts.account')

@section('content')
    <div class="container py-4"><h1 class="h3">Pedido {{ $order->order_number }}</h1><p class="text-secondary">{{ $order->order_status }} · {{ $order->payment_status }} · ${{ number_format((float) $order->grand_total, 0, ',', '.') }}</p><div class="card border-0 shadow-sm"><div class="card-body">@foreach($order->items as $item)<div class="d-flex justify-content-between border-bottom py-2"><span>{{ $item->quantity }} x {{ $item->product_name }} {{ $item->variant_name }}</span><strong>${{ number_format((float) $item->line_total, 0, ',', '.') }}</strong></div>@endforeach</div></div></div>
@endsection
