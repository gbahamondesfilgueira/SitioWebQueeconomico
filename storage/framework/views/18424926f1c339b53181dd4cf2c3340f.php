<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['categories', 'brands', 'originCountries', 'submitLabel' => 'Aplicar filtros']));

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

foreach (array_filter((['categories', 'brands', 'originCountries', 'submitLabel' => 'Aplicar filtros']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<form class="card border-0 shadow-sm">
    <?php if(request('q')): ?><input type="hidden" name="q" value="<?php echo e(request('q')); ?>"><?php endif; ?>
    <div class="card-header bg-white fw-semibold">Filtros</div>
    <div class="card-body">
        <label class="form-label">Categoría</label>
        <select name="category_id" class="form-select mb-3"><option value="">Todas</option><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($category->id); ?>" <?php if((int)request('category_id') === $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
        <label class="form-label">Marca</label>
        <select name="brand_id" class="form-select mb-3"><option value="">Todas</option><?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($brand->id); ?>" <?php if((int)request('brand_id') === $brand->id): echo 'selected'; endif; ?>><?php echo e($brand->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
        <div class="row g-2 mb-3"><div class="col"><label class="form-label">Precio mín.</label><input type="number" name="min_price" value="<?php echo e(request('min_price')); ?>" class="form-control"></div><div class="col"><label class="form-label">Precio máx.</label><input type="number" name="max_price" value="<?php echo e(request('max_price')); ?>" class="form-control"></div></div>
        <label class="form-label">País origen</label>
        <select name="origin_country_id" class="form-select mb-3"><option value="">Todos</option><?php $__currentLoopData = $originCountries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($country->id); ?>" <?php if((int)request('origin_country_id') === $country->id): echo 'selected'; endif; ?>><?php echo e($country->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
        <label class="form-check mb-2"><input type="checkbox" name="on_sale" value="1" class="form-check-input" <?php if(request()->boolean('on_sale')): echo 'checked'; endif; ?>> En oferta</label>
        <label class="form-check mb-2"><input type="checkbox" name="featured" value="1" class="form-check-input" <?php if(request()->boolean('featured')): echo 'checked'; endif; ?>> Destacados</label>
        <label class="form-check mb-3"><input type="checkbox" name="with_stock" value="1" class="form-check-input" <?php if(request()->boolean('with_stock')): echo 'checked'; endif; ?>> Con stock</label>
        <button class="btn btn-dark w-100"><?php echo e($submitLabel); ?></button>
    </div>
</form>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\components\store\filter-sidebar.blade.php ENDPATH**/ ?>