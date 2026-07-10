<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['cart']));

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

foreach (array_filter((['cart']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<div class="card border-0 shadow-sm mt-3">
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('store.cart.coupon.apply')); ?>" class="d-flex gap-2 mb-2">
            <?php echo csrf_field(); ?>
            <input name="coupon_code" class="form-control" placeholder="Código cupón">
            <button class="btn btn-outline-dark">Aplicar</button>
        </form>
        <?php $__currentLoopData = $cart->coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <form method="POST" action="<?php echo e(route('store.cart.coupon.remove')); ?>" class="d-flex justify-content-between align-items-center small">
                <?php echo csrf_field(); ?>
                <span><?php echo e($coupon->coupon_code); ?> · -$<?php echo e(number_format((float) $coupon->discount_amount, 0, ',', '.')); ?></span>
                <button class="btn btn-sm btn-link text-danger">Quitar</button>
            </form>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\components\store\coupon-form.blade.php ENDPATH**/ ?>