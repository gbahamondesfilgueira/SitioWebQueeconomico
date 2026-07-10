<?php $__env->startSection('title', 'Seguridad'); ?>
<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e(route('account.security.password')); ?>" class="card border-0 shadow-sm mb-3">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="card-header bg-white fw-semibold">Cambiar contraseña</div>
        <div class="card-body row g-3">
            <div class="col-md-4"><label class="form-label">Contraseña actual</label><input type="password" name="current_password" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label">Nueva contraseña</label><input type="password" name="password" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label">Confirmar</label><input type="password" name="password_confirmation" class="form-control" required></div>
            <?php if($errors->any()): ?><div class="col-12"><div class="alert alert-danger"><?php echo e($errors->first()); ?></div></div><?php endif; ?>
        </div>
        <div class="card-footer bg-white text-end"><button class="btn btn-dark">Actualizar</button></div>
    </form>
    <div class="card border-0 shadow-sm"><div class="card-header bg-white fw-semibold">Sesiones y doble autenticación</div><div class="card-body"><p class="text-secondary mb-2">Estructura preparada para cerrar sesiones activas, ver últimos accesos y activar doble autenticación.</p><span class="badge text-bg-light">2FA preparado</span></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.account', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\account\security.blade.php ENDPATH**/ ?>