<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['summary']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['summary']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold">Resumen</div>
    <div class="card-body">
        <div class="d-flex justify-content-between mb-2"><span>Subtotal regular</span><span>$<?php echo e(number_format($summary['subtotalRegular'], 0, ',', '.')); ?></span></div>
        <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><span>$<?php echo e(number_format($summary['subtotal'], 0, ',', '.')); ?></span></div>
        <div class="d-flex justify-content-between mb-2 text-success"><span>Descuentos items</span><span>-$<?php echo e(number_format($summary['itemDiscounts'], 0, ',', '.')); ?></span></div>
        <div class="d-flex justify-content-between mb-2 text-success"><span>Cupón</span><span>-$<?php echo e(number_format($summary['couponDiscount'], 0, ',', '.')); ?></span></div>
        <div class="d-flex justify-content-between mb-2"><span>Envío estimado</span><span>$<?php echo e(number_format($summary['shippingEstimate'], 0, ',', '.')); ?></span></div>
        <div class="d-flex justify-content-between mb-3"><span>IVA estimado</span><span>$<?php echo e(number_format($summary['taxTotal'], 0, ',', '.')); ?></span></div>
        <div class="d-flex justify-content-between fs-5 fw-bold border-top pt-3"><span>Total</span><span>$<?php echo e(number_format($summary['grandTotal'], 0, ',', '.')); ?></span></div>
    </div>
</div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\components\store\cart-summary.blade.php ENDPATH**/ ?>