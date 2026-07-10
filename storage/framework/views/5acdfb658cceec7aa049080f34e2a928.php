<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['step']));

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

foreach (array_filter((['step']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php ($steps = ['customer' => 'Cliente', 'shipping-address' => 'Despacho', 'billing-address' => 'Facturación', 'shipping-method' => 'Envío', 'payment-method' => 'Pago', 'review' => 'Revisión']); ?>
<div class="card border-0 shadow-sm mb-3"><div class="card-body d-flex flex-wrap gap-2"><?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span class="badge <?php echo e($step === $key ? 'text-bg-dark' : 'text-bg-light'); ?>"><?php echo e($label); ?></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div></div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\components\store\checkout-steps.blade.php ENDPATH**/ ?>