<?php $__env->startSection('title', 'Cajas POS'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="h4">Historial de cajas POS</h1></div>
<div class="card"><div class="card-body table-responsive">
<table class="table align-middle"><thead><tr><th>ID</th><th>Terminal</th><th>Abierta por</th><th>Estado</th><th>Inicial</th><th>Esperado</th><th>Diferencia</th><th>Fecha</th><th></th></tr></thead><tbody>
<?php $__empty_1 = true; $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr><td>#<?php echo e($session->id); ?></td><td><?php echo e($session->terminal?->name); ?></td><td><?php echo e($session->opener?->name); ?></td><td><?php echo e($session->status); ?></td><td>$<?php echo e(number_format($session->opening_amount, 0, ',', '.')); ?></td><td>$<?php echo e(number_format($session->expected_cash_amount, 0, ',', '.')); ?></td><td>$<?php echo e(number_format($session->cash_difference ?? 0, 0, ',', '.')); ?></td><td><?php echo e($session->opened_at->format('d/m/Y H:i')); ?></td><td><a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.cash-registers.show', $session)); ?>">Ver</a></td></tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <tr><td colspan="9" class="text-muted">Sin cajas registradas.</td></tr> <?php endif; ?>
</tbody></table><?php echo e($sessions->links()); ?></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\cash-registers\index.blade.php ENDPATH**/ ?>