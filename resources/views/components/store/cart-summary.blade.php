@props(['summary'])
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold">Resumen</div>
    <div class="card-body">
        <div class="d-flex justify-content-between mb-2"><span>Subtotal regular</span><span>${{ number_format($summary['subtotalRegular'], 0, ',', '.') }}</span></div>
        <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><span>${{ number_format($summary['subtotal'], 0, ',', '.') }}</span></div>
        <div class="d-flex justify-content-between mb-2 text-success"><span>Descuentos items</span><span>-${{ number_format($summary['itemDiscounts'], 0, ',', '.') }}</span></div>
        <div class="d-flex justify-content-between mb-2 text-success"><span>Cupón</span><span>-${{ number_format($summary['couponDiscount'], 0, ',', '.') }}</span></div>
        <div class="d-flex justify-content-between mb-2"><span>Envío estimado</span><span>${{ number_format($summary['shippingEstimate'], 0, ',', '.') }}</span></div>
        <div class="d-flex justify-content-between mb-3"><span>IVA estimado</span><span>${{ number_format($summary['taxTotal'], 0, ',', '.') }}</span></div>
        <div class="d-flex justify-content-between fs-5 fw-bold border-top pt-3"><span>Total</span><span>${{ number_format($summary['grandTotal'], 0, ',', '.') }}</span></div>
    </div>
</div>
