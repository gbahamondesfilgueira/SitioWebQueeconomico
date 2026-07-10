<?php $__env->startSection('title', 'Logs del sistema'); ?>
<?php $__env->startSection('page-title', 'Logs del sistema'); ?>
<?php $__env->startSection('content'); ?>
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Laravel log</div>
                <pre class="card-body small mb-0" style="max-height: 520px; overflow:auto;"><?php echo e($laravelLogs->implode("\n") ?: 'Sin logs disponibles.'); ?></pre>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Integraciones fallidas</div>
                <div class="list-group list-group-flush">
                    <?php $__empty_1 = true; $__currentLoopData = $integrationLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="list-group-item small"><?php echo e($log->integration?->name ?? '-'); ?>: <?php echo e($log->error_message ?: $log->event_type); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="list-group-item text-secondary">Sin errores.</div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Auditoría reciente</div>
                <div class="list-group list-group-flush">
                    <?php $__currentLoopData = $auditLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-group-item small"><?php echo e($log->created_at?->format('d/m/Y H:i')); ?> - <?php echo e($log->module); ?> - <?php echo e($log->action); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\system\logs.blade.php ENDPATH**/ ?>