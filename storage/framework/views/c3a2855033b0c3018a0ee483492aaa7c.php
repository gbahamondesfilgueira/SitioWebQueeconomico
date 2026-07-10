<?php $__env->startSection('title', 'Detalle backup'); ?>
<?php $__env->startSection('page-title', 'Detalle backup'); ?>
<?php $__env->startSection('content'); ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Tipo</dt><dd class="col-sm-9"><?php echo e($backup->backup_type); ?></dd>
                <dt class="col-sm-3">Estado</dt><dd class="col-sm-9"><?php echo e($backup->status); ?></dd>
                <dt class="col-sm-3">Archivo</dt><dd class="col-sm-9"><?php echo e($backup->file_path ?: '-'); ?></dd>
                <dt class="col-sm-3">Válido</dt><dd class="col-sm-9"><?php echo e($isValid ? 'Sí' : 'No'); ?></dd>
                <dt class="col-sm-3">Error</dt><dd class="col-sm-9"><?php echo e($backup->error_message ?: '-'); ?></dd>
            </dl>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\system\backups\show.blade.php ENDPATH**/ ?>