<?php $__env->startSection('title', 'Reporte de caja'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <h1 class="h4">Reporte diario de caja</h1>
    <button class="btn btn-outline-secondary" onclick="window.print()">Imprimir</button>
</div>
<div class="bg-white border rounded p-3 mb-3">
    <div><strong>Terminal:</strong> <?php echo e($session->terminal?->name); ?></div>
    <div><strong>Vendedor:</strong> <?php echo e($session->opener?->name); ?></div>
    <div><strong>Apertura:</strong> <?php echo e($session->opened_at->format('d/m/Y H:i')); ?></div>
    <div><strong>Cierre:</strong> <?php echo e($session->closed_at?->format('d/m/Y H:i') ?? 'Abierta'); ?></div>
    <div><strong>Estado:</strong> <?php echo e($session->status); ?></div>
</div>
<?php echo $__env->make('pos.cash.partials.totals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="bg-white border rounded p-3 mt-3">
    <h2 class="h5">Resultado de cierre</h2>
    <div class="d-flex justify-content-between"><span>Efectivo contado</span><strong>$<?php echo e(number_format($session->counted_cash_amount ?? 0, 0, ',', '.')); ?></strong></div>
    <div class="d-flex justify-content-between"><span>Diferencia</span><strong>$<?php echo e(number_format($session->cash_difference ?? 0, 0, ',', '.')); ?></strong></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\cash\report.blade.php ENDPATH**/ ?>