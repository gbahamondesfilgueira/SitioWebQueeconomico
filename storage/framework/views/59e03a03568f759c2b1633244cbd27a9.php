<?php $__env->startSection('title', $product->name); ?>
<?php $__env->startSection('page-title', 'Ficha de producto'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0"><?php echo e($product->name); ?></h1>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="<?php echo e(route('admin.products.index')); ?>">Volver</a>
            <a class="btn btn-primary" href="<?php echo e(route('admin.products.edit', $product)); ?>">Editar</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Resumen</div>
                <div class="card-body">
                    <dl class="mb-0">
                        <dt>SKU</dt><dd><?php echo e($product->sku ?? '-'); ?></dd>
                        <dt>Barcode</dt><dd><?php echo e($product->barcode ?? '-'); ?></dd>
                        <dt>Tipo</dt><dd><?php echo e($product->product_type === 'variable' ? 'Variable' : 'Simple'); ?></dd>
                        <dt>Precio final</dt><dd><?php echo e($product->getFinalPrice() !== null ? '$'.number_format($product->getFinalPrice(), 0, ',', '.') : '-'); ?></dd>
                        <dt>Peso facturable</dt><dd><?php echo e(number_format($product->getBillableWeight(), 3, ',', '.')); ?></dd>
                        <dt>Estado</dt><dd><?php echo e($product->is_active ? 'Activo' : 'Inactivo'); ?></dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Relaciones</div>
                <div class="card-body">
                    <p><strong>Categoría:</strong> <?php echo e($product->category?->name ?? '-'); ?></p>
                    <p><strong>Marca:</strong> <?php echo e($product->brand?->name ?? '-'); ?></p>
                    <p><strong>Proveedor:</strong> <?php echo e($product->supplier?->name ?? '-'); ?></p>
                    <p><strong>Etiquetas:</strong> <?php echo e($product->tags->pluck('name')->join(', ') ?: '-'); ?></p>
                    <p class="mb-0"><strong>Relacionados:</strong> <?php echo e($product->relatedProducts->pluck('name')->join(', ') ?: '-'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-3">
        <div class="card-header bg-white fw-semibold">Variantes</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>SKU</th><th>Nombre</th><th>Atributos</th><th>Precio</th><th>Estado</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $product->variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($variant->sku ?? '-'); ?></td>
                            <td><?php echo e($variant->name ?? '-'); ?></td>
                            <td><?php echo e($variant->attributeValues->map(fn($v) => $v->attribute->name.': '.$v->value)->join(' / ')); ?></td>
                            <td><?php echo e($variant->getFinalPrice() !== null ? '$'.number_format($variant->getFinalPrice(), 0, ',', '.') : '-'); ?></td>
                            <td><?php echo e($variant->is_active ? 'Activo' : 'Inactivo'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" class="text-center text-secondary py-4">Sin variantes.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\products\show.blade.php ENDPATH**/ ?>