<?php $__env->startSection('title', 'Variantes'); ?>
<?php $__env->startSection('page-title', 'Variantes de producto'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-0"><?php echo e($product->name); ?></h1>
            <div class="text-secondary small">Gestión de variantes</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="<?php echo e(route('admin.products.edit', $product)); ?>">Volver al producto</a>
            <a class="btn btn-primary" href="<?php echo e(route('admin.products.variants.create', $product)); ?>">Crear variante</a>
        </div>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th>SKU</th><th>Barcode</th><th>Nombre</th><th>Atributos</th><th>Precio</th><th>Peso facturable</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($variant->sku ?? '-'); ?></td>
                            <td><?php echo e($variant->barcode ?? '-'); ?></td>
                            <td><?php echo e($variant->name ?? '-'); ?></td>
                            <td><?php echo e($variant->attributeValues->map(fn($v) => $v->attribute->name.': '.$v->value)->join(' / ')); ?></td>
                            <td><?php echo e($variant->getFinalPrice() !== null ? '$'.number_format($variant->getFinalPrice(), 0, ',', '.') : '-'); ?></td>
                            <td><?php echo e(number_format($variant->getBillableWeight(), 3, ',', '.')); ?></td>
                            <td><span class="badge <?php echo e($variant->is_active ? 'text-bg-success' : 'text-bg-secondary'); ?>"><?php echo e($variant->is_active ? 'Activo' : 'Inactivo'); ?></span></td>
                            <td class="text-end"><div class="btn-group">
                                <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.products.variants.edit', [$product, $variant])); ?>"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="<?php echo e(route('admin.products.variants.toggle-active', [$product, $variant])); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><button class="btn btn-sm btn-outline-warning"><i class="bi bi-person-dash"></i></button></form>
                                <form method="POST" action="<?php echo e(route('admin.products.variants.destroy', [$product, $variant])); ?>" onsubmit="return confirm('¿Enviar variante a papelera?');"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                            </div></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="8" class="text-center text-secondary py-4">Este producto todavía no tiene variantes.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($variants->hasPages()): ?><div class="card-footer bg-white"><?php echo e($variants->links()); ?></div><?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\products\variants\index.blade.php ENDPATH**/ ?>