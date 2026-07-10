<?php $__env->startSection('title', 'Ventas POS'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4">Ventas POS</h1>
    <a class="btn btn-primary" href="<?php echo e(route('pos.sale.create')); ?>">Nueva venta</a>
</div>
<p class="text-muted">Las ventas POS quedan registradas en el módulo de pedidos con canal POS.</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\sales\index.blade.php ENDPATH**/ ?>