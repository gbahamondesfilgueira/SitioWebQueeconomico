<?php $__env->startSection('title', 'Usuarios'); ?>
<?php $__env->startSection('page-title', 'Usuarios'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Gestión de usuarios</h1>
        <a class="btn btn-primary" href="<?php echo e(route('admin.users.create')); ?>">
            <i class="bi bi-plus-lg me-1"></i>Crear usuario
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($user->name); ?></td>
                            <td><?php echo e($user->email); ?></td>
                            <td><?php echo e($user->phone ?? '-'); ?></td>
                            <td><?php echo e($user->role?->name); ?></td>
                            <td>
                                <span class="badge <?php echo e($user->is_active ? 'text-bg-success' : 'text-bg-secondary'); ?>">
                                    <?php echo e($user->is_active ? 'Activo' : 'Inactivo'); ?>

                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.users.edit', $user)); ?>">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="<?php echo e(route('admin.users.toggle-active', $user)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button class="btn btn-sm <?php echo e($user->is_active ? 'btn-outline-warning' : 'btn-outline-success'); ?>" type="submit">
                                            <i class="bi <?php echo e($user->is_active ? 'bi-person-dash' : 'bi-person-check'); ?>"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-4">No hay usuarios registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($users->hasPages()): ?>
            <div class="card-footer bg-white"><?php echo e($users->links()); ?></div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\users\index.blade.php ENDPATH**/ ?>