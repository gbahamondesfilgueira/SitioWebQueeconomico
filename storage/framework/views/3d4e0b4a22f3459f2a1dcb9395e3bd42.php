<?php $__env->startSection('title', 'Movimientos de caja'); ?>
<?php $__env->startSection('content'); ?>
<h1 class="h4 mb-3">Movimientos de caja</h1>
<div class="bg-white border rounded p-3 table-responsive">
<table class="table align-middle"><thead><tr><th>Fecha</th><th>Tipo</th><th>Monto</th><th>Medio</th><th>Descripción</th><th>Usuario</th></tr></thead><tbody>
<?php $__currentLoopData = $session->movements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr><td><?php echo e($movement->created_at->format('d/m/Y H:i')); ?></td><td><?php echo e($movement->movement_type); ?></td><td>$<?php echo e(number_format($movement->amount, 0, ',', '.')); ?></td><td><?php echo e($movement->paymentMethod?->name ?? '-'); ?></td><td><?php echo e($movement->description); ?></td><td><?php echo e($movement->user?->name); ?></td></tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody></table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\cash\movements.blade.php ENDPATH**/ ?>