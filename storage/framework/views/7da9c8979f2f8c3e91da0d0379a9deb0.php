<?php $__env->startSection('title', 'Crear cliente'); ?>
<?php $__env->startSection('page-title', 'Crear cliente'); ?>

<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e(route('admin.customers.store')); ?>" class="card border-0 shadow-sm">
        <?php echo csrf_field(); ?>
        <div class="card-body">
            <?php echo $__env->make('admin.customers.partials.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <div class="card-footer bg-white d-flex justify-content-end gap-2">
            <a href="<?php echo e(route('admin.customers.index')); ?>" class="btn btn-outline-secondary">Cancelar</a>
            <button class="btn btn-dark">Guardar cliente</button>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\customers\create.blade.php ENDPATH**/ ?>