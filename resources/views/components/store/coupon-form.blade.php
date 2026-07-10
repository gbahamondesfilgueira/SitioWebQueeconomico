@props(['cart'])
<div class="card border-0 shadow-sm mt-3">
    <div class="card-body">
        <form method="POST" action="{{ route('store.cart.coupon.apply') }}" class="d-flex gap-2 mb-2">
            @csrf
            <input name="coupon_code" class="form-control" placeholder="Código cupón">
            <button class="btn btn-outline-dark">Aplicar</button>
        </form>
        @foreach($cart->coupons as $coupon)
            <form method="POST" action="{{ route('store.cart.coupon.remove') }}" class="d-flex justify-content-between align-items-center small">
                @csrf
                <span>{{ $coupon->coupon_code }} · -${{ number_format((float) $coupon->discount_amount, 0, ',', '.') }}</span>
                <button class="btn btn-sm btn-link text-danger">Quitar</button>
            </form>
        @endforeach
    </div>
</div>
