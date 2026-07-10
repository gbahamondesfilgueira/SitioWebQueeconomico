<?php $__env->startSection('title', 'Categorías'); ?>
<?php $__env->startSection('page-title', 'Categorías'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Categorías</h1>
        <a class="btn btn-primary" href="<?php echo e(route('admin.categories.create')); ?>">
            <i class="bi bi-plus-lg me-1"></i>Crear categoría
        </a>
    </div>

    <form class="card border-0 shadow-sm mb-3" method="GET">
        <div class="card-body">
            <div class="input-group">
                <input class="form-control" name="search" value="<?php echo e($search); ?>" placeholder="Buscar por nombre, slug o descripción...">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Orden</th>
                        <th>Nombre</th>
                        <th>Slug</th>
                        <th>Padre</th>
                        <th>Hijas</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($category->sort_order); ?></td>
                            <td><?php echo e($category->name); ?></td>
                            <td><?php echo e($category->slug); ?></td>
                            <td><?php echo e($category->parent?->name ?? '-'); ?></td>
                            <td><?php echo e($category->children_count); ?></td>
                            <td>
                                <span class="badge <?php echo e($category->is_active ? 'text-bg-success' : 'text-bg-secondary'); ?>">
                                    <?php echo e($category->is_active ? 'Activo' : 'Inactivo'); ?>

                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.categories.edit', $category)); ?>">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="<?php echo e(route('admin.categories.toggle-active', $category)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button class="btn btn-sm <?php echo e($category->is_active ? 'btn-outline-warning' : 'btn-outline-success'); ?>" type="submit">
                                            <i class="bi <?php echo e($category->is_active ? 'bi-person-dash' : 'bi-person-check'); ?>"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="<?php echo e(route('admin.categories.destroy', $category)); ?>" onsubmit="return confirm('¿Eliminar esta categoría?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-outline-danger" type="submit" <?php if($category->children_count > 0): echo 'disabled'; endif; ?>>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-secondary py-4">No hay categorías registradas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($categories->hasPages()): ?>
            <div class="card-footer bg-white"><?php echo e($categories->links()); ?></div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\categories\index.blade.php ENDPATH**/ ?>