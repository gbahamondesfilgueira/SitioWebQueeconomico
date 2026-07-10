<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['product', 'display']));

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

foreach (array_filter((['product', 'display']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<div class="card border-0 shadow-sm">
    <div class="ratio ratio-1x1 bg-light">
        <?php if($display['image']): ?>
            <img src="<?php echo e($display['image']); ?>" alt="<?php echo e($product->name); ?>" class="object-fit-cover">
        <?php else: ?>
            <div class="d-flex align-items-center justify-content-center text-secondary">Sin imagen</div>
        <?php endif; ?>
    </div>
    <?php if($product->images->count() > 1): ?>
        <div class="card-body d-flex gap-2 flex-wrap"><?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><img src="<?php echo e(Storage::url($image->image_path)); ?>" alt="<?php echo e($image->alt_text ?: $product->name); ?>" style="width:64px;height:64px" class="object-fit-cover rounded border"><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
    <?php endif; ?>
</div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\components\store\product-gallery.blade.php ENDPATH**/ ?>