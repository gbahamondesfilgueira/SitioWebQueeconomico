<?php $__env->startSection('title', 'Anulación POS'); ?>
<?php $__env->startSection('content'); ?>
<div class="bg-white border rounded p-4">
<h1 class="h4">Anulación registrada</h1>
<p><strong>Venta:</strong> <?php echo e($cancellation->order->order_number); ?></p>
<p><strong>Motivo:</strong> <?php echo e($cancellation->reason); ?></p>
<p><strong>Usuario:</strong> <?php echo e($cancellation->user?->name); ?></p>
<p><strong>Fecha:</strong> <?php echo e($cancellation->cancelled_at->format('d/m/Y H:i')); ?></p>
<a class="btn btn-primary" href="<?php echo e(route('pos.cash.current')); ?>">Volver a caja</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\cancellations\show.blade.php ENDPATH**/ ?>