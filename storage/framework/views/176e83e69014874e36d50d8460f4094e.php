<?php $__env->startSection('title', 'Importaciones masivas'); ?>
<?php $__env->startSection('page-title', 'Importaciones masivas'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Importaciones masivas</h1>
            <p class="text-secondary mb-0">Carga productos, categorías, marcas, etiquetas, atributos y variantes desde CSV WooCommerce.</p>
        </div>
        <a href="<?php echo e(route('admin.imports.products.create')); ?>" class="btn btn-dark">Subir CSV</a>
    </div>

    <?php if (isset($component)) { $__componentOriginala509139e89a677cd12f14634ef88c660 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala509139e89a677cd12f14634ef88c660 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.report-table','data' => ['columns' => ['Fecha','Tipo','Estado','Filas','Correctas','Fallidas','Usuario','Ver'],'rows' => $imports->map(fn($import) => [
            $import->created_at?->format('d/m/Y H:i'),
            $import->import_type,
            $import->status,
            $import->total_rows,
            $import->successful_rows,
            $import->failed_rows,
            $import->creator?->name ?? '-',
            route('admin.imports.products.show', $import),
        ]),'paginator' => $imports]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.report-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Fecha','Tipo','Estado','Filas','Correctas','Fallidas','Usuario','Ver']),'rows' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($imports->map(fn($import) => [
            $import->created_at?->format('d/m/Y H:i'),
            $import->import_type,
            $import->status,
            $import->total_rows,
            $import->successful_rows,
            $import->failed_rows,
            $import->creator?->name ?? '-',
            route('admin.imports.products.show', $import),
        ])),'paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($imports)]); ?>
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\imports\products\index.blade.php ENDPATH**/ ?>