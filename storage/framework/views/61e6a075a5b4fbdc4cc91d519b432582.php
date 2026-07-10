<?php $__env->startSection('title', 'Productos'); ?>
<?php $__env->startSection('page-title', 'Productos'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Productos inteligentes</h1>
        <a class="btn btn-primary" href="<?php echo e(route('admin.products.create')); ?>"><i class="bi bi-plus-lg me-1"></i>Crear producto</a>
    </div>

    <form class="card border-0 shadow-sm mb-3" method="GET">
        <div class="card-body">
            <div class="input-group">
                <input class="form-control" name="search" value="<?php echo e($search); ?>" placeholder="Buscar por nombre, SKU, barcode o slug...">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>SKU</th>
                        <th>Tipo</th>
                        <th>Categoría</th>
                        <th>Marca</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Destacado</th>
                        <th>Visible</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $primary = $product->images->firstWhere('is_primary', true) ?? $product->images->first(); ?>
                        <tr>
                            <td>
                                <?php if($primary): ?>
                                    <img src="<?php echo e(asset('storage/'.$primary->image_path)); ?>" alt="<?php echo e($product->name); ?>" width="52" height="52" class="rounded object-fit-cover">
                                <?php else: ?>
                                    <span class="badge text-bg-light"><i class="bi bi-image"></i></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-semibold"><?php echo e($product->name); ?></div>
                                <div class="small text-secondary"><?php echo e($product->variants_count); ?> variantes</div>
                            </td>
                            <td><?php echo e($product->sku ?? '-'); ?></td>
                            <td><span class="badge text-bg-light"><?php echo e($product->product_type === 'variable' ? 'Variable' : 'Simple'); ?></span></td>
                            <td><?php echo e($product->category?->name ?? '-'); ?></td>
                            <td><?php echo e($product->brand?->name ?? '-'); ?></td>
                            <td>
                                <?php if($product->hasActiveSale()): ?>
                                    <span class="text-danger fw-semibold">$<?php echo e(number_format($product->getFinalPrice(), 0, ',', '.')); ?></span>
                                    <span class="small text-secondary text-decoration-line-through">$<?php echo e(number_format((float) $product->regular_price, 0, ',', '.')); ?></span>
                                <?php elseif($product->regular_price): ?>
                                    $<?php echo e(number_format((float) $product->regular_price, 0, ',', '.')); ?>

                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><span class="badge <?php echo e($product->is_active ? 'text-bg-success' : 'text-bg-secondary'); ?>"><?php echo e($product->is_active ? 'Activo' : 'Inactivo'); ?></span></td>
                            <td><?php echo e($product->is_featured ? 'Sí' : 'No'); ?></td>
                            <td><?php echo e($product->is_visible ? 'Sí' : 'No'); ?></td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(route('admin.products.show', $product)); ?>"><i class="bi bi-eye"></i></a>
                                    <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.products.edit', $product)); ?>"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="<?php echo e(route('admin.products.toggle-active', $product)); ?>">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                        <button class="btn btn-sm <?php echo e($product->is_active ? 'btn-outline-warning' : 'btn-outline-success'); ?>" type="submit">
                                            <i class="bi <?php echo e($product->is_active ? 'bi-person-dash' : 'bi-person-check'); ?>"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="11" class="text-center text-secondary py-4">No hay productos creados todavía.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($products->hasPages()): ?>
            <div class="card-footer bg-white"><?php echo e($products->links()); ?></div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\products\index.blade.php ENDPATH**/ ?>