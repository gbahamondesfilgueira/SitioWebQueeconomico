<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['display']));

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

foreach (array_filter((['display']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<div>
    <?php if($display['discount_percentage'] > 0): ?>
        <div class="small text-secondary text-decoration-line-through">$<?php echo e(number_format($display['regular_price'], 0, ',', '.')); ?></div>
        <div class="fs-4 fw-bold text-danger">$<?php echo e(number_format($display['final_price'], 0, ',', '.')); ?></div>
        <span class="badge text-bg-danger">-<?php echo e($display['discount_percentage']); ?>%</span>
    <?php else: ?>
        <div class="fs-5 fw-bold">$<?php echo e(number_format($display['final_price'], 0, ',', '.')); ?></div>
    <?php endif; ?>
</div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\components\store\price.blade.php ENDPATH**/ ?>