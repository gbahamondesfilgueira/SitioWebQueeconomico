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
<?php ($product = $display['product']); ?>
<?php ($defaultVariant = collect($display['variants'] ?? [])->firstWhere('stock', '>', 0) ?: collect($display['variants'] ?? [])->first()); ?>
<div class="card h-100 border-0 shadow-sm product-card">
    <a href="<?php echo e(route('store.products.show', $product->slug)); ?>" class="ratio ratio-1x1 bg-light text-decoration-none">
        <?php if($display['image']): ?>
            <img src="<?php echo e($display['image']); ?>" alt="<?php echo e($product->name); ?>" class="card-img-top object-fit-cover">
        <?php else: ?>
            <div class="d-flex align-items-center justify-content-center text-secondary">Sin imagen</div>
        <?php endif; ?>
    </a>
    <div class="card-body">
        <div class="small text-secondary"><?php echo e($product->brand?->name); ?></div>
        <a href="<?php echo e(route('store.products.show', $product->slug)); ?>" class="text-dark text-decoration-none fw-semibold"><?php echo e($product->name); ?></a>
        <div class="mt-2"><?php if (isset($component)) { $__componentOriginal067ed8b6e5ef0df3ae9aa247904fdf86 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal067ed8b6e5ef0df3ae9aa247904fdf86 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.price','data' => ['display' => $display]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.price'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['display' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($display)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal067ed8b6e5ef0df3ae9aa247904fdf86)): ?>
<?php $attributes = $__attributesOriginal067ed8b6e5ef0df3ae9aa247904fdf86; ?>
<?php unset($__attributesOriginal067ed8b6e5ef0df3ae9aa247904fdf86); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal067ed8b6e5ef0df3ae9aa247904fdf86)): ?>
<?php $component = $__componentOriginal067ed8b6e5ef0df3ae9aa247904fdf86; ?>
<?php unset($__componentOriginal067ed8b6e5ef0df3ae9aa247904fdf86); ?>
<?php endif; ?></div>
        <div class="mt-2"><span class="badge <?php echo e($display['stock_class']); ?>"><?php echo e($display['stock_label']); ?></span></div>
    </div>
    <div class="card-footer bg-white border-0 pt-0">
        <form method="POST" action="<?php echo e(route('store.cart.add')); ?>" data-cart-add-form>
            <?php echo csrf_field(); ?>
            <input type="hidden" name="item_type" value="product">
            <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
            <?php if($product->product_type === 'variable' && $defaultVariant): ?>
                <input type="hidden" name="product_variant_id" value="<?php echo e($defaultVariant['id']); ?>">
            <?php endif; ?>
            <input type="hidden" name="quantity" value="1">
            <button class="btn btn-outline-dark w-100" <?php if($display['stock'] <= 0): echo 'disabled'; endif; ?>>Agregar</button>
        </form>
    </div>
</div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\components\store\product-card.blade.php ENDPATH**/ ?>