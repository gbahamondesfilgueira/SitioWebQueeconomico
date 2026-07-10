<?php $__env->startSection('title', 'Marcas'); ?>
<?php $__env->startSection('page-title', 'Marcas'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Marcas</h1>
        <a class="btn btn-primary" href="<?php echo e(route('admin.brands.create')); ?>">
            <i class="bi bi-plus-lg me-1"></i>Crear marca
        </a>
    </div>

    <form class="card border-0 shadow-sm mb-3" method="GET">
        <div class="card-body">
            <div class="input-group">
                <input class="form-control" name="search" value="<?php echo e($search); ?>" placeholder="Buscar por nombre, slug o sitio web...">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Slug</th>
                        <th>Sitio web</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($brand->name); ?></td>
                            <td><?php echo e($brand->slug); ?></td>
                            <td><?php echo e($brand->website ? parse_url($brand->website, PHP_URL_HOST) : '-'); ?></td>
                            <td>
                                <span class="badge <?php echo e($brand->is_active ? 'text-bg-success' : 'text-bg-secondary'); ?>">
                                    <?php echo e($brand->is_active ? 'Activo' : 'Inactivo'); ?>

                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.brands.edit', $brand)); ?>">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="<?php echo e(route('admin.brands.toggle-active', $brand)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button class="btn btn-sm <?php echo e($brand->is_active ? 'btn-outline-warning' : 'btn-outline-success'); ?>" type="submit">
                                            <i class="bi <?php echo e($brand->is_active ? 'bi-person-dash' : 'bi-person-check'); ?>"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="<?php echo e(route('admin.brands.destroy', $brand)); ?>" onsubmit="return confirm('¿Eliminar esta marca?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-outline-danger" type="submit">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-4">No hay marcas registradas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($brands->hasPages()): ?>
            <div class="card-footer bg-white"><?php echo e($brands->links()); ?></div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\brands\index.blade.php ENDPATH**/ ?>