<?php $__env->startSection('title', 'Caja actual'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h1 class="h4 mb-0">Caja actual</h1><div class="text-muted"><?php echo e($terminal->name); ?> · Apertura <?php echo e($session->opened_at->format('d/m/Y H:i')); ?></div></div>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-primary" href="<?php echo e(route('pos.cash.income')); ?>">Ingreso</a>
        <a class="btn btn-outline-warning" href="<?php echo e(route('pos.cash.expense')); ?>">Egreso / retiro</a>
        <a class="btn btn-outline-secondary" href="<?php echo e(route('pos.cash.report')); ?>">Reporte</a>
        <a class="btn btn-danger" href="<?php echo e(route('pos.cash.close')); ?>">Cerrar caja</a>
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Monto inicial</div><div class="h3">$<?php echo e(number_format($summary['opening'], 0, ',', '.')); ?></div></div></div>
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Ventas</div><div class="h3">$<?php echo e(number_format($summary['sales'], 0, ',', '.')); ?></div></div></div>
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Efectivo esperado</div><div class="h3">$<?php echo e(number_format($summary['cashExpected'], 0, ',', '.')); ?></div></div></div>
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Estado</div><div class="h3"><?php echo e($session->status === 'open' ? 'Abierta' : 'Cerrada'); ?></div></div></div>
</div>
<?php echo $__env->make('pos.cash.partials.totals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\cash\current.blade.php ENDPATH**/ ?>