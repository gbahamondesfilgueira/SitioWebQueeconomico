<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold">Revisión final</div>
    <div class="card-body">
        <h2 class="h6">Items</h2>
        @foreach($cart->items as $item)
            <div class="d-flex justify-content-between border-top py-2"><span>{{ $item->quantity }} x {{ $item->item_type === 'pack' ? $item->pack?->name : $item->product?->name }}</span><strong>${{ number_format((float) $item->line_total, 0, ',', '.') }}</strong></div>
        @endforeach
        <h2 class="h6 mt-4">Despacho y pago</h2>
        <p class="mb-1">Envío: {{ $cart->shippingMethod?->service_name ?? 'No seleccionado' }}</p>
        <p class="mb-1">Pago: {{ $cart->paymentMethod?->payment_label ?? 'No seleccionado' }}</p>
        <div class="alert alert-info mt-3 mb-0">La compra queda lista para crear pedido en Fase 9. Aún no se consume stock ni se procesa pago real.</div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between">
        <a href="{{ route('store.checkout.payment-method') }}" class="btn btn-outline-secondary">Volver</a>
        <form method="POST" action="{{ route('store.checkout.confirm') }}">@csrf<button class="btn btn-dark">Confirmar pedido</button></form>
    </div>
</div>
