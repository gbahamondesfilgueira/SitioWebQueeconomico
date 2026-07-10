<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['variants' => []]));

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

foreach (array_filter((['variants' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php if(count($variants)): ?>
    <div class="mb-3">
        <label class="form-label fw-semibold">Variantes</label>
        <select class="form-select" id="variantSelector">
            <?php $__currentLoopData = $variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($variant['id']); ?>"><?php echo e($variant['name']); ?> · $<?php echo e(number_format($variant['final_price'], 0, ',', '.')); ?> · <?php echo e($variant['stock_label']); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <div class="small text-secondary mt-2">El selector queda preparado para actualizar precio e imagen en la fase de carrito.</div>
    </div>
<?php endif; ?>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\components\store\variant-selector.blade.php ENDPATH**/ ?>