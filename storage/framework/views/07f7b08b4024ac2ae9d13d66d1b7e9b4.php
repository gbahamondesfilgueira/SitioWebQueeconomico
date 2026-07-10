<?php $__env->startSection('title','Crear pack'); ?>
<?php $__env->startSection('page-title','Crear pack'); ?>
<?php $__env->startSection('content'); ?><form method="POST" action="<?php echo e(route('admin.product-packs.store')); ?>" enctype="multipart/form-data"><?php echo csrf_field(); ?> <?php echo $__env->make('admin.product_packs.partials.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></form><?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\product_packs\create.blade.php ENDPATH**/ ?>