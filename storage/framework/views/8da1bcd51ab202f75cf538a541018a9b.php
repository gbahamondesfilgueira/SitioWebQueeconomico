<?php $__env->startSection('title', 'Atributos'); ?>
<?php $__env->startSection('page-title', 'Atributos'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Atributos de productos</h1>
        <a class="btn btn-primary" href="<?php echo e(route('admin.attributes.create')); ?>">Crear atributo</a>
    </div>
    <form class="card border-0 shadow-sm mb-3" method="GET"><div class="card-body"><div class="input-group"><input class="form-control" name="search" value="<?php echo e($search); ?>" placeholder="Buscar..."><button class="btn btn-outline-secondary">Buscar</button></div></div></form>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th>Nombre</th><th>Slug</th><th>Tipo</th><th>Valores</th><th>Orden</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($attribute->name); ?></td><td><?php echo e($attribute->slug); ?></td><td><?php echo e($attribute->type); ?></td><td><?php echo e($attribute->values_count); ?></td><td><?php echo e($attribute->sort_order); ?></td>
                            <td><span class="badge <?php echo e($attribute->is_active ? 'text-bg-success' : 'text-bg-secondary'); ?>"><?php echo e($attribute->is_active ? 'Activo' : 'Inactivo'); ?></span></td>
                            <td class="text-end"><div class="btn-group">
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(route('admin.attributes.show', $attribute)); ?>">Valores</a>
                                <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.attributes.edit', $attribute)); ?>"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="<?php echo e(route('admin.attributes.toggle-active', $attribute)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><button class="btn btn-sm btn-outline-warning"><i class="bi bi-person-dash"></i></button></form>
                            </div></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center text-secondary py-4">No hay atributos.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($attributes->hasPages()): ?><div class="card-footer bg-white"><?php echo e($attributes->links()); ?></div><?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\attributes\index.blade.php ENDPATH**/ ?>