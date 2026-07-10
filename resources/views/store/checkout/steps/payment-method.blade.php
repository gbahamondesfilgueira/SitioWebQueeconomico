<form method="POST" action="{{ route('store.checkout.payment-method') }}" class="card border-0 shadow-sm">
    @csrf
    <div class="card-header bg-white fw-semibold">Método de pago</div>
    <div class="card-body">
        @foreach($paymentOptions as $key => $option)
            <label class="card border mb-2"><div class="card-body"><input type="radio" name="payment_method" value="{{ $key }}" class="form-check-input me-2" @checked($loop->first)> {{ $option['payment_label'] }}</div></label>
        @endforeach
    </div>
    <div class="card-footer bg-white d-flex justify-content-between"><a href="{{ route('store.checkout.shipping-method') }}" class="btn btn-outline-secondary">Volver</a><button class="btn btn-dark">Continuar</button></div>
</form>
