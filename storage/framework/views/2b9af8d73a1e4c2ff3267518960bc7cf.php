<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['reportType', 'filters' => []]));

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

foreach (array_filter((['reportType', 'filters' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $base = array_filter($filters, fn ($value) => $value !== null && $value !== '');
?>

<div class="btn-group">
    <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route('admin.reports.export', array_merge($base, ['report' => $reportType, 'format' => 'csv']))); ?>">CSV</a>
    <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route('admin.reports.export', array_merge($base, ['report' => $reportType, 'format' => 'excel']))); ?>">Excel</a>
    <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route('admin.reports.export', array_merge($base, ['report' => $reportType, 'format' => 'pdf']))); ?>" target="_blank">PDF</a>
</div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\components\admin\report-export-buttons.blade.php ENDPATH**/ ?>