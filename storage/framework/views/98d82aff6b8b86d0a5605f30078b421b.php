<?php $__env->startSection('title', 'Stock actual'); ?>
<?php $__env->startSection('page-title', 'Stock actual'); ?>

<?php $__env->startSection('content'); ?>
    <form class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="input-group">
                <input class="form-control" name="search" value="<?php echo e($search); ?>" placeholder="Buscar producto o SKU...">
                <button class="btn btn-outline-secondary">Buscar</button>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Variante</th>
                        <th>Bodega</th>
                        <th>Ubicacion</th>
                        <th>Fisico</th>
                        <th>Reservado</th>
                        <th>Disponible</th>
                        <th>Minimo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($level->product->name); ?></td>
                            <td><?php echo e($level->variant?->sku ?? '-'); ?></td>
                            <td><?php echo e($level->warehouse->name); ?></td>
                            <td><?php echo e($level->location?->name ?? '-'); ?></td>
                            <td><?php echo e((int) $level->physical_stock); ?></td>
                            <td><?php echo e((int) $level->reserved_stock); ?></td>
                            <td class="<?php echo e($level->available_stock <= $level->minimum_stock ? 'text-danger fw-semibold' : ''); ?>"><?php echo e((int) $level->available_stock); ?></td>
                            <td><?php echo e((int) $level->minimum_stock); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="8" class="text-center text-secondary py-4">No hay stock registrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($levels->hasPages()): ?>
            <div class="card-footer bg-white"><?php echo e($levels->links()); ?></div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\stock\index.blade.php ENDPATH**/ ?>