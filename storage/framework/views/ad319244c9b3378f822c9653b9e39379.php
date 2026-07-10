<?php $__env->startSection('title', 'Auditoría'); ?>
<?php $__env->startSection('page-title', 'Auditoría'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold">Registros de auditoría</div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Módulo</th>
                        <th>Descripción</th>
                        <th>IP</th>
                        <th>User Agent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $auditLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="small text-secondary"><?php echo e($log->created_at?->format('d/m/Y H:i:s')); ?></td>
                            <td><?php echo e($log->user?->name ?? 'Sistema'); ?></td>
                            <td><span class="badge text-bg-light"><?php echo e($log->action); ?></span></td>
                            <td><?php echo e($log->module); ?></td>
                            <td><?php echo e($log->description); ?></td>
                            <td><?php echo e($log->ip_address); ?></td>
                            <td class="small text-secondary text-truncate" style="max-width: 260px;"><?php echo e($log->user_agent); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-secondary py-4">No hay registros de auditoría.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($auditLogs->hasPages()): ?>
            <div class="card-footer bg-white"><?php echo e($auditLogs->links()); ?></div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\audit_logs\index.blade.php ENDPATH**/ ?>