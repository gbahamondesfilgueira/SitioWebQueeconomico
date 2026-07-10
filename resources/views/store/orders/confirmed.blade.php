@extends('layouts.store')

@section('content')
    <div class="container py-5">
        @php
            $payment = $order->payments->first();
            $whatsappText = 'Hola, Este es el numero '.$order->order_number.' de pedido, cuales son los datos bancarios bancarios para transferir';
            $whatsappUrl = 'https://wa.me/56950031384?text='.rawurlencode($whatsappText);
        @endphp
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3">Pedido confirmado</h1>
                <p class="text-secondary">Tu pedido {{ $order->order_number }} fue creado correctamente.</p>
                <div class="row g-3">
                    <div class="col-md-4"><strong>Total</strong><div class="fs-4">${{ number_format((float) $order->grand_total, 0, ',', '.') }}</div></div>
                    <div class="col-md-4"><strong>Estado</strong><div>{{ $order->order_status }}</div></div>
                    <div class="col-md-4"><strong>Pago</strong><div>{{ $payment?->payment_label }}</div></div>
                </div>
                @if($payment?->payment_method === 'bank_transfer')
                    <div class="alert alert-warning mt-4">
                        Para completar tu compra por transferencia, escríbenos por WhatsApp indicando tu número de pedido.
                        <div class="mt-3">
                            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="btn btn-success">Solicitar datos bancarios por WhatsApp</a>
                        </div>
                    </div>
                @endif
                <hr>
                @foreach($order->items as $item)<div class="d-flex justify-content-between py-2 border-bottom"><span>{{ $item->quantity }} x {{ $item->product_name }} {{ $item->variant_name }}</span><strong>${{ number_format((float) $item->line_total, 0, ',', '.') }}</strong></div>@endforeach
                <a href="{{ route('store.home') }}" class="btn btn-dark mt-3">Volver a la tienda</a>
            </div>
        </div>
    </div>
@endsection
