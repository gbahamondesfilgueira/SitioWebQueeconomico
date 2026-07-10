<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['filters' => [], 'showChannel' => true, 'showStatus' => true]));

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

foreach (array_filter((['filters' => [], 'showChannel' => true, 'showStatus' => true]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<form method="GET" class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Fecha desde</label>
                <input type="date" name="date_from" value="<?php echo e($filters['date_from'] ?? ''); ?>" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha hasta</label>
                <input type="date" name="date_to" value="<?php echo e($filters['date_to'] ?? ''); ?>" class="form-control">
            </div>
            <?php if($showChannel): ?>
                <div class="col-md-3">
                    <label class="form-label">Canal</label>
                    <select name="channel" class="form-select">
                        <option value="">Todos</option>
                        <?php $__currentLoopData = ['ecommerce' => 'Ecommerce', 'pos' => 'POS', 'manual' => 'Manual']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php if(($filters['channel'] ?? '') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            <?php endif; ?>
            <?php if($showStatus): ?>
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <input type="text" name="status" value="<?php echo e($filters['status'] ?? ''); ?>" class="form-control" placeholder="Estado">
                </div>
            <?php endif; ?>
            <div class="col-md-3">
                <button class="btn btn-dark w-100" type="submit">Filtrar</button>
            </div>
        </div>
    </div>
</form>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\components\admin\report-filters.blade.php ENDPATH**/ ?>