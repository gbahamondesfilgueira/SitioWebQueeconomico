<?php $__env->startSection('title', 'Crear moneda'); ?>
<?php $__env->startSection('page-title', 'Crear moneda'); ?>
<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e(route('admin.currencies.store')); ?>" class="card border-0 shadow-sm"><div class="card-body"><?php echo $__env->make('admin.currencies.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div></form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\currencies\create.blade.php ENDPATH**/ ?>