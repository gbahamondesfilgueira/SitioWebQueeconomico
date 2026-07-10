<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'chart']));

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

foreach (array_filter((['title', 'chart']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="card border-0 shadow-sm h-100">
    <div class="card-body">
        <h3 class="h6 mb-3"><?php echo e($title); ?></h3>
        <?php
            $max = max(collect($chart['values'] ?? [0])->map(fn ($value) => (float) $value)->max() ?: 1, 1);
        ?>
        <div class="d-flex flex-column gap-2">
            <?php $__currentLoopData = ($chart['labels'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $value = (float) (($chart['values'][$index] ?? 0));
                    $width = max(4, ($value / $max) * 100);
                ?>
                <div>
                    <div class="d-flex justify-content-between small">
                        <span><?php echo e($label); ?></span>
                        <span><?php echo e(number_format($value, 0, ',', '.')); ?></span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-dark" style="width: <?php echo e($width); ?>%"></div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\components\admin\report-chart.blade.php ENDPATH**/ ?>