<?php $__env->startSection('title', 'Crear categoría'); ?>
<?php $__env->startSection('page-title', 'Crear categoría'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('admin.categories.store')); ?>" enctype="multipart/form-data" class="row g-3">
                <?php echo csrf_field(); ?>
                <?php echo $__env->make('admin.categories.partials.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\categories\create.blade.php ENDPATH**/ ?>