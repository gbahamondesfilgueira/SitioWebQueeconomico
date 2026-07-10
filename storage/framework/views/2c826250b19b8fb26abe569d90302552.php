<?php $__env->startSection('title', 'Devolución registrada'); ?>
<?php $__env->startSection('content'); ?>
<div class="bg-white border rounded p-4">
<h1 class="h4">Devolución registrada</h1>
<p><strong>Venta:</strong> <?php echo e($refund->order->order_number); ?></p>
<p><strong>Monto:</strong> $<?php echo e(number_format($refund->refund_amount, 0, ',', '.')); ?></p>
<p><strong>Motivo:</strong> <?php echo e($refund->reason); ?></p>
<p><strong>Procesado por:</strong> <?php echo e($refund->processor?->name); ?></p>
<a class="btn btn-primary" href="<?php echo e(route('pos.cash.current')); ?>">Volver a caja</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\refunds\show.blade.php ENDPATH**/ ?>