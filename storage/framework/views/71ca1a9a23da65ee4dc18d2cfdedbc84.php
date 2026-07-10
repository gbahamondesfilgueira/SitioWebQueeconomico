<?php $__env->startSection('title', 'Seguridad usuarios'); ?>
<?php $__env->startSection('page-title', 'Seguridad usuarios'); ?>
<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginala509139e89a677cd12f14634ef88c660 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala509139e89a677cd12f14634ef88c660 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.report-table','data' => ['columns' => ['Usuario','Email','Rol','Activo','Último login','IP','Fallidos','Bloqueado hasta'],'rows' => $users->map(fn($u) => [$u->name,$u->email,$u->role?->name ?? '-', $u->is_active ? 'Sí' : 'No', $u->last_login_at?->format('d/m/Y H:i') ?: '-', $u->last_login_ip ?: '-', $u->failed_login_attempts, $u->locked_until?->format('d/m/Y H:i') ?: '-']),'paginator' => $users]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.report-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Usuario','Email','Rol','Activo','Último login','IP','Fallidos','Bloqueado hasta']),'rows' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($users->map(fn($u) => [$u->name,$u->email,$u->role?->name ?? '-', $u->is_active ? 'Sí' : 'No', $u->last_login_at?->format('d/m/Y H:i') ?: '-', $u->last_login_ip ?: '-', $u->failed_login_attempts, $u->locked_until?->format('d/m/Y H:i') ?: '-'])),'paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($users)]); ?>
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\security\users.blade.php ENDPATH**/ ?>