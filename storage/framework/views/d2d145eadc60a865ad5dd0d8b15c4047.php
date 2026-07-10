<?php $__env->startSection('title', 'Dashboard Ejecutivo'); ?>
<?php $__env->startSection('page-title', 'Dashboard Ejecutivo'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1">Dashboard Ejecutivo</h1>
            <p class="text-secondary mb-0">Resumen diario para ventas, pedidos, clientes, POS e inventario.</p>
        </div>
    </div>

    <?php if (isset($component)) { $__componentOriginal378c4e4a7c150b5a67a80d7e37bc194d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal378c4e4a7c150b5a67a80d7e37bc194d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.report-filters','data' => ['filters' => $filters,'showStatus' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.report-filters'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($filters),'show-status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
        <?php $__currentLoopData = $dashboard['cards']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if (isset($component)) { $__componentOriginal3221eb96c25326b9e447aa604abd5d73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3221eb96c25326b9e447aa604abd5d73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.report-card','data' => ['label' => $card['label'],'value' => $card['value']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.report-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($card['label']),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($card['value'])]); ?>
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

    <div class="row g-3">
        <?php $__currentLoopData = $dashboard['charts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $title => $chart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-6">
                <?php if (isset($component)) { $__componentOriginal99ec3c4375312a9b82f6921215738d9e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal99ec3c4375312a9b82f6921215738d9e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.report-chart','data' => ['title' => $title,'chart' => $chart]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.report-chart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title),'chart' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($chart)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal99ec3c4375312a9b82f6921215738d9e)): ?>
<?php $attributes = $__attributesOriginal99ec3c4375312a9b82f6921215738d9e; ?>
<?php unset($__attributesOriginal99ec3c4375312a9b82f6921215738d9e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal99ec3c4375312a9b82f6921215738d9e)): ?>
<?php $component = $__componentOriginal99ec3c4375312a9b82f6921215738d9e; ?>
<?php unset($__componentOriginal99ec3c4375312a9b82f6921215738d9e); ?>
<?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\reports\dashboard.blade.php ENDPATH**/ ?>