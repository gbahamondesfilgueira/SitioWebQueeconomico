<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['item']));

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

foreach (array_filter((['item']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body d-flex gap-3 align-items-center">
        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:84px;height:84px">
            <?php ($image = $item->product?->images?->first()?->image_path); ?>
            <?php if($image): ?><img src="<?php echo e(Storage::url($image)); ?>" class="object-fit-cover rounded" style="width:84px;height:84px" alt=""><?php else: ?><span class="small text-secondary">Item</span><?php endif; ?>
        </div>
        <div class="flex-grow-1">
            <div class="fw-semibold"><?php echo e($item->item_type === 'pack' ? $item->pack?->name : $item->product?->name); ?></div>
            <div class="small text-secondary"><?php echo e($item->variant?->name ?: $item->variant?->sku); ?></div>
            <div class="small"><?php if($item->line_discount > 0): ?><span class="text-decoration-line-through text-secondary">$<?php echo e(number_format((float) $item->regular_price, 0, ',', '.')); ?></span><?php endif; ?> <span class="fw-semibold">$<?php echo e(number_format((float) $item->final_unit_price, 0, ',', '.')); ?></span></div>
        </div>
        <form method="POST" action="<?php echo e(route('store.cart.update')); ?>" class="d-flex gap-2 align-items-center">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="cart_item_id" value="<?php echo e($item->id); ?>">
            <input type="number" name="quantity" value="<?php echo e($item->quantity); ?>" min="1" class="form-control" style="width:90px">
            <button class="btn btn-outline-secondary btn-sm">Actualizar</button>
        </form>
        <div class="fw-bold">$<?php echo e(number_format((float) $item->line_total, 0, ',', '.')); ?></div>
        <form method="POST" action="<?php echo e(route('store.cart.remove')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="cart_item_id" value="<?php echo e($item->id); ?>">
            <button class="btn btn-outline-danger btn-sm">Eliminar</button>
        </form>
    </div>
</div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\components\store\cart-item.blade.php ENDPATH**/ ?>