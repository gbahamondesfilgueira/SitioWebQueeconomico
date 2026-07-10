<?php $__env->startSection('title', 'Empresas'); ?>
<?php $__env->startSection('page-title', 'Empresas'); ?>
<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-end mb-3"><a href="<?php echo e(route('admin.companies.create')); ?>" class="btn btn-dark">Crear empresa</a></div>
    <?php if (isset($component)) { $__componentOriginala509139e89a677cd12f14634ef88c660 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala509139e89a677cd12f14634ef88c660 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.report-table','data' => ['columns' => ['Nombre','RUT','Email','Moneda','IVA','Estado','Editar'],'rows' => $companies->map(fn($c) => [$c->name,$c->rut ?: '-',$c->email ?: '-',$c->currency,$c->tax_percentage.'%',$c->is_active ? 'Activa' : 'Inactiva', route('admin.companies.edit', $c)]),'paginator' => $companies]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.report-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Nombre','RUT','Email','Moneda','IVA','Estado','Editar']),'rows' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($companies->map(fn($c) => [$c->name,$c->rut ?: '-',$c->email ?: '-',$c->currency,$c->tax_percentage.'%',$c->is_active ? 'Activa' : 'Inactiva', route('admin.companies.edit', $c)])),'paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($companies)]); ?>
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\companies\index.blade.php ENDPATH**/ ?>