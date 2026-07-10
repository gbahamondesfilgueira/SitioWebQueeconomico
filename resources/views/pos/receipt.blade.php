<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comprobante {{ $order->order_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>@media print {.no-print{display:none}.receipt{max-width:320px}}</style>
</head>
<body class="bg-light">
<main class="receipt mx-auto bg-white p-3 my-3 border">
    <div class="text-center">
        <h1 class="h5 mb-0">{{ $settings->store_name }}</h1>
        <div class="small">{{ $settings->rut }} · {{ $settings->address }}</div>
    </div>
    <hr>
    <div class="small">
        <div><strong>Venta:</strong> {{ $order->order_number }}</div>
        <div><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</div>
        <div><strong>Vendedor:</strong> {{ $order->seller?->name }}</div>
        <div><strong>Terminal:</strong> {{ $order->posTerminal?->name }}</div>
        <div><strong>Cliente:</strong> {{ $order->customer_name }}</div>
    </div>
    <hr>
    @foreach($order->items as $item)
        <div class="d-flex justify-content-between small">
            <span>{{ $item->quantity }} x {{ $item->product_name }} {{ $item->variant_name }}</span>
            <strong>${{ number_format($item->line_total, 0, ',', '.') }}</strong>
        </div>
    @endforeach
    <hr>
    <div class="d-flex justify-content-between"><span>Descuentos</span><span>${{ number_format($order->item_discount_total + $order->coupon_discount_total, 0, ',', '.') }}</span></div>
    <div class="d-flex justify-content-between"><span>IVA</span><span>${{ number_format($order->tax_total, 0, ',', '.') }}</span></div>
    <div class="d-flex justify-content-between h5"><span>Total</span><strong>${{ number_format($order->grand_total, 0, ',', '.') }}</strong></div>
    <hr>
    <div class="small">
        @foreach($order->payments as $payment)
            <div>{{ $payment->payment_label }}: ${{ number_format($payment->amount, 0, ',', '.') }}</div>
        @endforeach
    </div>
    <p class="text-center mt-3">{{ $settings->pos_default_receipt_message ?? 'Gracias por su compra' }}</p>
    <div class="d-grid gap-2 no-print">
        <button class="btn btn-primary" onclick="window.print()">Imprimir</button>
        <a class="btn btn-outline-secondary" href="{{ route('pos.sale.create') }}">Nueva venta</a>
    </div>
</main>
</body>
</html>
