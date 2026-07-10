<?php $__env->startSection('title', 'Backups'); ?>
<?php $__env->startSection('page-title', 'Backups'); ?>
<?php $__env->startSection('content'); ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('admin.system.backups.store')); ?>" class="row g-3 align-items-end">
                <?php echo csrf_field(); ?>
                <div class="col-md-4">
                    <label class="form-label">Tipo de backup</label>
                    <select name="backup_type" class="form-select">
                        <option value="database">Base de datos</option>
                        <option value="files">Archivos</option>
                        <option value="full">Completo</option>
                    </select>
                </div>
                <div class="col-md-3"><button class="btn btn-dark w-100">Crear backup</button></div>
            </form>
        </div>
    </div>
    <?php if (isset($component)) { $__componentOriginala509139e89a677cd12f14634ef88c660 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala509139e89a677cd12f14634ef88c660 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.report-table','data' => ['columns' => ['Tipo','Estado','Archivo','Tamaño','Inicio','Fin','Ver'],'rows' => $backups->map(fn($b) => [$b->backup_type,$b->status,$b->file_path ?: '-', $b->file_size ? number_format($b->file_size / 1024, 1, ',', '.').' KB' : '-', $b->started_at?->format('d/m/Y H:i') ?: '-', $b->finished_at?->format('d/m/Y H:i') ?: '-', route('admin.system.backups.show', $b)]),'paginator' => $backups]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.report-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Tipo','Estado','Archivo','Tamaño','Inicio','Fin','Ver']),'rows' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($backups->map(fn($b) => [$b->backup_type,$b->status,$b->file_path ?: '-', $b->file_size ? number_format($b->file_size / 1024, 1, ',', '.').' KB' : '-', $b->started_at?->format('d/m/Y H:i') ?: '-', $b->finished_at?->format('d/m/Y H:i') ?: '-', route('admin.system.backups.show', $b)])),'paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($backups)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala509139e89a677cd12f14634ef88c660)): ?>
<?php $attributes = $__attributesOriginala509139e89a677cd12f14634ef88c660; ?>
<?php unset($__attributesOriginala509139e89a677cd12f14634ef88c660); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala509139e89a677cd12f14634ef88c660)): ?>
<?php $component = $__componentOriginala509139e89a677cd12f14634ef88c660; ?>
<?php unset($__componentOriginala509139e89a677cd12f14634ef88c660); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\system\backups\index.blade.php ENDPATH**/ ?>