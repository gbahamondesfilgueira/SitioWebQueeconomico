<?php $__env->startSection('title', 'Editar moneda'); ?>
<?php $__env->startSection('page-title', 'Editar moneda'); ?>
<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e(route('admin.currencies.update', $currency)); ?>" class="card border-0 shadow-sm"><?php echo method_field('PUT'); ?><div class="card-body"><?php echo $__env->make('admin.currencies.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div></form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\currencies\edit.blade.php ENDPATH**/ ?>