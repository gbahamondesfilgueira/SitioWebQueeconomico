<?php $__env->startSection('title', $title); ?>
<?php $__env->startSection('page-title', $title); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1"><?php echo e($title); ?></h1>
            <p class="text-secondary mb-0">Informacion calculada desde registros reales del sistema.</p>
        </div>
        <?php if (isset($component)) { $__componentOriginal844c562c9fde367e3f867756baa6adf2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal844c562c9fde367e3f867756baa6adf2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.report-export-buttons','data' => ['reportType' => $reportType,'filters' => $filters]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.report-export-buttons'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['report-type' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($reportType),'filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($filters)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal844c562c9fde367e3f867756baa6adf2)): ?>
<?php $attributes = $__attributesOriginal844c562c9fde367e3f867756baa6adf2; ?>
<?php unset($__attributesOriginal844c562c9fde367e3f867756baa6adf2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal844c562c9fde367e3f867756baa6adf2)): ?>
<?php $component = $__componentOriginal844c562c9fde367e3f867756baa6adf2; ?>
<?php unset($__componentOriginal844c562c9fde367e3f867756baa6adf2); ?>
<?php endif; ?>
    </div>

    <?php if (isset($component)) { $__componentOriginal378c4e4a7c150b5a67a80d7e37bc194d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal378c4e4a7c150b5a67a80d7e37bc194d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.report-filters','data' => ['filters' => $filters]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.report-filters'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($filters)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal378c4e4a7c150b5a67a80d7e37bc194d)): ?>
<?php $attributes = $__attributesOriginal378c4e4a7c150b5a67a80d7e37bc194d; ?>
<?php unset($__attributesOriginal378c4e4a7c150b5a67a80d7e37bc194d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal378c4e4a7c150b5a67a80d7e37bc194d)): ?>
<?php $component = $__componentOriginal378c4e4a7c150b5a67a80d7e37bc194d; ?>
<?php unset($__componentOriginal378c4e4a7c150b5a67a80d7e37bc194d); ?>
<?php endif; ?>

    <div class="row g-3 mb-4">
        <?php $__currentLoopData = ($report['summary'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if (isset($component)) { $__componentOriginal3221eb96c25326b9e447aa604abd5d73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3221eb96c25326b9e447aa604abd5d73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.report-card','data' => ['label' => $label,'value' => $value]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.report-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($label),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value)]); ?>
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

    <?php if (isset($component)) { $__componentOriginala509139e89a677cd12f14634ef88c660 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala509139e89a677cd12f14634ef88c660 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.report-table','data' => ['columns' => $report['columns'] ?? [],'rows' => $report['rows'] ?? [],'paginator' => $report['paginator'] ?? null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.report-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($report['columns'] ?? []),'rows' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($report['rows'] ?? []),'paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($report['paginator'] ?? null)]); ?>
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\reports\partials\report-page.blade.php ENDPATH**/ ?>