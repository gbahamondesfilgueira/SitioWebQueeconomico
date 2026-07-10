<?php $__env->startSection('title', 'Reservas POS'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="h4">Reservas POS</h1><a class="btn btn-primary" href="<?php echo e(route('pos.sale.create')); ?>">Nueva venta</a></div>
<div class="bg-white border rounded p-3 table-responsive">
<table class="table align-middle"><thead><tr><th>Número</th><th>Cliente</th><th>Estado</th><th>Vence</th><th></th></tr></thead><tbody>
<?php $__empty_1 = true; $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr><td><?php echo e($reservation->reservation_number); ?></td><td><?php echo e($reservation->customer?->display_name ?? 'Mostrador'); ?></td><td><?php echo e($reservation->status); ?></td><td><?php echo e($reservation->expires_at?->format('d/m/Y H:i')); ?></td><td><a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('pos.reservations.show', $reservation)); ?>">Ver</a></td></tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <tr><td colspan="5" class="text-muted">Sin reservas.</td></tr> <?php endif; ?>
</tbody></table><?php echo e($reservations->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\reservations\index.blade.php ENDPATH**/ ?>