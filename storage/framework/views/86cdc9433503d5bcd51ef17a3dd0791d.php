<?php $__env->startSection('title','Importación'); ?>
<?php $__env->startSection('page-title','Importación #'.$import->id); ?>
<?php $__env->startSection('content'); ?>
    <div class="card border-0 shadow-sm"><div class="card-body"><p>Estado: <?php echo e($import->status); ?></p><p>Filas: <?php echo e($import->successful_rows); ?> correctas, <?php echo e($import->failed_rows); ?> fallidas.</p><?php if($import->error_report_path): ?><a href="<?php echo e(route('admin.shipping.imports.errors', $import)); ?>" class="btn btn-outline-danger">Reporte errores</a><?php endif; ?></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\shipping\imports\show.blade.php ENDPATH**/ ?>