<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['category']));

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

foreach (array_filter((['category']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<a href="<?php echo e(route('store.categories.show', $category->slug)); ?>" class="card border-0 shadow-sm text-decoration-none text-dark h-100">
    <div class="ratio ratio-16x9 bg-light">
        <?php if($category->image_path): ?>
            <img src="<?php echo e(Storage::url($category->image_path)); ?>" alt="<?php echo e($category->name); ?>" class="object-fit-cover">
        <?php else: ?>
            <div class="d-flex align-items-center justify-content-center fw-semibold"><?php echo e($category->name); ?></div>
        <?php endif; ?>
    </div>
    <div class="card-body"><div class="fw-semibold"><?php echo e($category->name); ?></div><div class="small text-secondary"><?php echo e($category->description); ?></div></div>
</a>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\components\store\category-card.blade.php ENDPATH**/ ?>