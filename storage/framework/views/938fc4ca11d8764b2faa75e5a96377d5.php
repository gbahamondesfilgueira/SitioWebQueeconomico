<?php $__env->startSection('title', 'Nueva venta'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-lg-8">
        <div class="bg-white border rounded p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <h1 class="h4 mb-0">Venta POS</h1>
                    <small class="text-muted"><?php echo e($terminal->name); ?> / Bodega <?php echo e($terminal->warehouse->name ?? ''); ?></small>
                </div>
                <form method="POST" action="<?php echo e(route('pos.cart.clear')); ?>"><?php echo csrf_field(); ?><button class="btn btn-outline-danger">Vaciar</button></form>
            </div>
            <?php echo $__env->make('pos.components.product-search', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('pos.components.cart-items', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
    <div class="col-lg-4">
        <?php echo $__env->make('pos.components.customer-panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('pos.components.payment-panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('pos.components.summary', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\sales\create.blade.php ENDPATH**/ ?>