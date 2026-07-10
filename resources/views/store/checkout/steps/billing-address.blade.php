<form method="POST" action="{{ route('store.checkout.billing-address') }}" class="card border-0 shadow-sm">
    @csrf
    <div class="card-header bg-white fw-semibold">Dirección de facturación</div>
    <div class="card-body">
        <label class="form-check mb-3"><input type="checkbox" name="same_as_shipping" value="1" class="form-check-input" data-same-as-shipping> Usar la misma direccion de despacho</label>
        @if($companies->isNotEmpty())
            <label class="form-label">Empresa para factura</label>
            <select name="customer_company_id" class="form-select mb-3"><option value="">Persona natural</option>@foreach($companies as $company)<option value="{{ $company->id }}">{{ $company->company_name }} · {{ $company->rut }}</option>@endforeach</select>
        @endif
        <x-store.address-form type="billing" :saved-addresses="$savedAddresses" />
    </div>
    <div class="card-footer bg-white d-flex justify-content-between"><a href="{{ route('store.checkout.shipping-address') }}" class="btn btn-outline-secondary">Volver</a><button class="btn btn-dark">Continuar</button></div>
</form>
