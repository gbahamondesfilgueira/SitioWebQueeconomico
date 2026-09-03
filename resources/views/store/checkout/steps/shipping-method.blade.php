<form method="POST" action="{{ route('store.checkout.shipping-method') }}" class="card border-0 shadow-sm">
    @csrf
    <div class="card-header bg-white fw-semibold">Método de envío</div>
    <div class="card-body">
        @if(($shippingQuotes ?? collect())->isNotEmpty())
            <div class="mb-3">
                <div class="fw-semibold mb-2">Cotizaciones disponibles</div>
                @foreach($shippingQuotes as $quote)
                    <label class="card border mb-2"><div class="card-body d-flex justify-content-between align-items-center"><span><input type="radio" name="shipping_method" value="quote:{{ $quote->id }}" class="form-check-input me-2" @checked($loop->first)> {{ $quote->carrier?->name }} · {{ $quote->service?->name }} <span class="text-secondary small">{{ $quote->estimated_days_min }}-{{ $quote->estimated_days_max }} día(s)</span>@if(data_get($quote->metadata, 'multi_origin')) <span class="badge text-bg-info ms-1">{{ data_get($quote->metadata, 'package_count') }} bodegas</span>@endif</span><strong>${{ number_format((float) $quote->price, 0, ',', '.') }}</strong></div></label>
                @endforeach
            </div>
        @endif
        <div class="fw-semibold mb-2">Opciones manuales</div>
        @foreach($shippingOptions as $key => $option)
            <label class="card border mb-2"><div class="card-body d-flex justify-content-between align-items-center"><span><input type="radio" name="shipping_method" value="{{ $key }}" class="form-check-input me-2" @checked(($shippingQuotes ?? collect())->isEmpty() && $loop->first)> {{ $option['service_name'] }} <span class="text-secondary small">{{ $option['estimated_days_min'] }}-{{ $option['estimated_days_max'] }} días hábiles</span></span><strong>${{ number_format($option['estimated_price'], 0, ',', '.') }}</strong></div></label>
        @endforeach
    </div>
    <div class="card-footer bg-white d-flex justify-content-between"><a href="{{ route('store.checkout.billing-address') }}" class="btn btn-outline-secondary">Volver</a><button class="btn btn-dark">Continuar</button></div>
</form>
