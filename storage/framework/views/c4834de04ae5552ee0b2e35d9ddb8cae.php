<?php $__env->startSection('title', 'Resultado importación'); ?>
<?php $__env->startSection('page-title', 'Resultado importación'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Resultado importación #<?php echo e($import->id); ?></h1>
            <p class="text-secondary mb-0"><?php echo e($import->file_path); ?></p>
        </div>
        <a href="<?php echo e(route('admin.imports.products.create')); ?>" class="btn btn-dark">Subir otro CSV</a>
    </div>

    <div class="row g-3 mb-4">
        <?php if (isset($component)) { $__componentOriginal3221eb96c25326b9e447aa604abd5d73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3221eb96c25326b9e447aa604abd5d73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.report-card','data' => ['label' => 'Estado','value' => $import->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.report-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Estado','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($import->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3221eb96c25326b9e447aa604abd5d73)): ?>
<?php $attributes = $__attributesOriginal3221eb96c25326b9e447aa604abd5d73; ?>
<?php unset($__attributesOriginal3221eb96c25326b9e447aa604abd5d73); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3221eb96c25326b9e447aa604abd5d73)): ?>
<?php $component = $__componentOriginal3221eb96c25326b9e447aa604abd5d73; ?>
<?php unset($__componentOriginal3221eb96c25326b9e447aa604abd5d73); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal3221eb96c25326b9e447aa604abd5d73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3221eb96c25326b9e447aa604abd5d73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.report-card','data' => ['label' => 'Filas','value' => $import->total_rows]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.report-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Filas','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($import->total_rows)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3221eb96c25326b9e447aa604abd5d73)): ?>
<?php $attributes = $__attributesOriginal3221eb96c25326b9e447aa604abd5d73; ?>
<?php unset($__attributesOriginal3221eb96c25326b9e447aa604abd5d73); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3221eb96c25326b9e447aa604abd5d73)): ?>
<?php $component = $__componentOriginal3221eb96c25326b9e447aa604abd5d73; ?>
<?php unset($__componentOriginal3221eb96c25326b9e447aa604abd5d73); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal3221eb96c25326b9e447aa604abd5d73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3221eb96c25326b9e447aa604abd5d73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.report-card','data' => ['label' => 'Correctas','value' => $import->successful_rows]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.report-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Correctas','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($import->successful_rows)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3221eb96c25326b9e447aa604abd5d73)): ?>
<?php $attributes = $__attributesOriginal3221eb96c25326b9e447aa604abd5d73; ?>
<?php unset($__attributesOriginal3221eb96c25326b9e447aa604abd5d73); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3221eb96c25326b9e447aa604abd5d73)): ?>
<?php $component = $__componentOriginal3221eb96c25326b9e447aa604abd5d73; ?>
<?php unset($__componentOriginal3221eb96c25326b9e447aa604abd5d73); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal3221eb96c25326b9e447aa604abd5d73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3221eb96c25326b9e447aa604abd5d73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.report-card','data' => ['label' => 'Fallidas','value' => $import->failed_rows]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.report-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Fallidas','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($import->failed_rows)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3221eb96c25326b9e447aa604abd5d73)): ?>
<?php $attributes = $__attributesOriginal3221eb96c25326b9e447aa604abd5d73; ?>
<?php unset($__attributesOriginal3221eb96c25326b9e447aa604abd5d73); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3221eb96c25326b9e447aa604abd5d73)): ?>
<?php $component = $__componentOriginal3221eb96c25326b9e447aa604abd5d73; ?>
<?php unset($__componentOriginal3221eb96c25326b9e447aa604abd5d73); ?>
<?php endif; ?>
    </div>

    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Resumen</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <?php $__currentLoopData = ($import->summary ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <dt class="col-8"><?php echo e(str_replace('_', ' ', $key)); ?></dt>
                            <dd class="col-4 text-end"><?php echo e($value); ?></dd>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Errores</div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>Fila</th><th>Producto</th><th>Error</th></tr></thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = ($import->errors ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($error['row'] ?? '-'); ?></td>
                                    <td><?php echo e($error['name'] ?? '-'); ?></td>
                                    <td><?php echo e($error['message'] ?? '-'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="3" class="text-secondary text-center py-4">Sin errores registrados.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\imports\products\show.blade.php ENDPATH**/ ?>