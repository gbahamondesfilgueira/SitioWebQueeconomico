<form method="POST" action="{{ route('store.checkout.shipping-address') }}" class="card border-0 shadow-sm" data-checkout-shipping-form>
    @csrf
    <div class="card-header bg-white fw-semibold">Dirección de despacho</div>
    <div class="card-body"><x-store.address-form type="shipping" :saved-addresses="$savedAddresses" /></div>
    <div class="card-footer bg-white d-flex justify-content-between"><a href="{{ route('store.checkout.index') }}" class="btn btn-outline-secondary">Volver</a><button class="btn btn-dark">Continuar</button></div>
</form>
