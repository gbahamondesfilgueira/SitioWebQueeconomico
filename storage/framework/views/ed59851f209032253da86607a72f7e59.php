<?php $__env->startSection('title', 'Permisos'); ?>
<?php $__env->startSection('page-title', 'Permisos'); ?>
<?php $__env->startSection('content'); ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('admin.security.permissions.store')); ?>" class="row g-3">
                <?php echo csrf_field(); ?>
                <div class="col-md-3"><input name="name" class="form-control" placeholder="Nombre" required></div>
                <div class="col-md-3"><input name="slug" class="form-control" placeholder="slug_permiso" required></div>
                <div class="col-md-2"><input name="module" class="form-control" placeholder="Módulo" required></div>
                <div class="col-md-3"><input name="description" class="form-control" placeholder="Descripción"></div>
                <div class="col-md-1"><button class="btn btn-dark w-100">Crear</button></div>
            </form>
        </div>
    </div>
    <div class="row g-3">
        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-6">
                <form method="POST" action="<?php echo e(route('admin.security.roles.permissions', $role)); ?>" class="card border-0 shadow-sm h-100">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="card-header bg-white fw-semibold"><?php echo e($role->name); ?></div>
                    <div class="card-body">
                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="<?php echo e($permission->id); ?>" <?php if($role->permissions->contains($permission)): echo 'checked'; endif; ?>>
                                <label class="form-check-label"><?php echo e($permission->name); ?> <span class="text-secondary small">(<?php echo e($permission->module); ?>)</span></label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="card-footer bg-white"><button class="btn btn-dark btn-sm">Guardar permisos</button></div>
                </form>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\security\permissions.blade.php ENDPATH**/ ?>