<?php $__env->startSection('title', 'Crear empresa'); ?>
<?php $__env->startSection('page-title', 'Crear empresa'); ?>
<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e(route('admin.companies.store')); ?>" class="card border-0 shadow-sm"><div class="card-body"><?php echo $__env->make('admin.companies.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div></form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\companies\create.blade.php ENDPATH**/ ?>