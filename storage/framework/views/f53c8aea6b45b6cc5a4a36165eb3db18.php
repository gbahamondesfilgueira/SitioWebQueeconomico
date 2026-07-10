<?php $__env->startSection('title', 'Estado del sistema'); ?>
<?php $__env->startSection('page-title', 'Estado del sistema'); ?>
<?php $__env->startSection('content'); ?>
    <div class="row g-3 mb-4">
        <?php $__currentLoopData = $checks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $check): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if (isset($component)) { $__componentOriginal3221eb96c25326b9e447aa604abd5d73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3221eb96c25326b9e447aa604abd5d73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.report-card','data' => ['label' => $check['check_name'],'value' => strtoupper($check['status']).' - '.$check['message']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.report-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($check['check_name']),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(strtoupper($check['status']).' - '.$check['message'])]); ?>
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
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h2 class="h5">Últimos errores</h2>
            <pre class="bg-light p-3 small mb-0" style="max-height: 360px; overflow:auto;"><?php echo e(implode("\n", $latest['errors']) ?: 'Sin errores recientes.'); ?></pre>
            <p class="text-secondary mt-3 mb-0">Uso aproximado de storage: <?php echo e(number_format(($latest['storage_usage'] ?? 0) / 1024 / 1024, 2, ',', '.')); ?> MB</p>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\system\health.blade.php ENDPATH**/ ?>