<?php $__env->startSection('title', $config['title']); ?>
<?php $__env->startSection('page-title', $config['title']); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0"><?php echo e($config['title']); ?></h1>
        <a class="btn btn-primary" href="<?php echo e(route($config['routePrefix'].'.create')); ?>">
            <i class="bi bi-plus-lg me-1"></i>Crear
        </a>
    </div>

    <form class="card border-0 shadow-sm mb-3" method="GET">
        <div class="card-body">
            <div class="input-group">
                <input class="form-control" name="search" value="<?php echo e($search); ?>" placeholder="Buscar...">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <?php $__currentLoopData = $config['columns']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th><?php echo e($label); ?></th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if($config['hasActiveToggle']): ?>
                            <th>Estado</th>
                        <?php endif; ?>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <?php $__currentLoopData = $config['columns']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td>
                                    <?php if(is_bool($item->{$key})): ?>
                                        <span class="badge <?php echo e($item->{$key} ? 'text-bg-primary' : 'text-bg-light'); ?>"><?php echo e($item->{$key} ? 'Sí' : 'No'); ?></span>
                                    <?php elseif($key === 'percentage'): ?>
                                        <?php echo e(number_format((float) $item->{$key}, 2, ',', '.')); ?>%
                                    <?php elseif($key === 'type'): ?>
                                        <?php echo e($config['typeOptions'][$item->{$key}] ?? $item->{$key}); ?>

                                    <?php else: ?>
                                        <?php echo e($item->{$key} ?? '-'); ?>

                                    <?php endif; ?>
                                </td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($config['hasActiveToggle']): ?>
                                <td>
                                    <span class="badge <?php echo e($item->is_active ? 'text-bg-success' : 'text-bg-secondary'); ?>">
                                        <?php echo e($item->is_active ? 'Activo' : 'Inactivo'); ?>

                                    </span>
                                </td>
                            <?php endif; ?>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route($config['routePrefix'].'.edit', $item)); ?>">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if($config['hasActiveToggle']): ?>
                                        <form method="POST" action="<?php echo e(route($config['routePrefix'].'.toggle-active', $item)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button class="btn btn-sm <?php echo e($item->is_active ? 'btn-outline-warning' : 'btn-outline-success'); ?>" type="submit">
                                                <i class="bi <?php echo e($item->is_active ? 'bi-person-dash' : 'bi-person-check'); ?>"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if($config['allowDelete']): ?>
                                        <form method="POST" action="<?php echo e(route($config['routePrefix'].'.destroy', $item)); ?>" onsubmit="return confirm('¿Eliminar este registro?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-outline-danger" type="submit">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="<?php echo e(count($config['columns']) + ($config['hasActiveToggle'] ? 2 : 1)); ?>" class="text-center text-secondary py-4">
                                No hay registros disponibles.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($items->hasPages()): ?>
            <div class="card-footer bg-white"><?php echo e($items->links()); ?></div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\catalog\index.blade.php ENDPATH**/ ?>