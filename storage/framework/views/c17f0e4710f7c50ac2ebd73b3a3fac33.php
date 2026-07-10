<?php $__env->startSection('title','Crear ajuste'); ?>
<?php $__env->startSection('page-title','Crear ajuste'); ?>
<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('admin.stock-adjustments.store')); ?>" class="row g-3">
            <?php echo csrf_field(); ?>
            <div class="col-md-6">
                <label class="form-label">Bodega</label>
                <select class="form-select" name="warehouse_id" required>
                    <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($w->id); ?>"><?php echo e($w->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Bodega / ubicacion de stock</label>
                <select class="form-select" name="warehouse_location_id">
                    <option value="">Sin ubicacion especifica</option>
                    <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $__currentLoopData = $w->locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($l->id); ?>"><?php echo e($w->code); ?> / <?php echo e($l->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <div class="form-text">La ubicacion permite indicar en que bodega o zona fisica se encuentra el stock del producto. El stock disponible para venta queda asignado a una ubicacion activa.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Producto</label>
                <select class="form-select" name="product_id" required>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($p->id); ?>" <?php if(old('product_id', $selectedProductId ?? null) == $p->id): echo 'selected'; endif; ?>><?php echo e($p->name); ?> <?php if($p->sku): ?> / <?php echo e($p->sku); ?> <?php endif; ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <div class="form-text">Para cargar stock a un producto recien creado, crea este ajuste como aumento y luego apruebalo.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Variante</label>
                <select class="form-select" name="product_variant_id">
                    <option value="">Producto simple</option>
                    <?php $__currentLoopData = $variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($v->id); ?>" <?php if(old('product_variant_id', $selectedVariantId ?? null) == $v->id): echo 'selected'; endif; ?>><?php echo e($v->product->name); ?> / <?php echo e($v->sku ?: $v->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <div class="form-text">Si el producto es variable, selecciona la variante exacta antes de guardar.</div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tipo</label>
                <select class="form-select" name="adjustment_type"><option value="increase">Aumentar</option><option value="decrease">Disminuir</option></select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Cantidad</label>
                <input class="form-control" name="quantity" type="number" min="1" step="1" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Motivo</label>
                <select class="form-select" name="reason">
                    <?php $__currentLoopData = ['correction' => 'Correccion','damage' => 'Merma/daño','loss' => 'Perdida','inventory_count' => 'Conteo inventario','return' => 'Devolucion','other' => 'Otro']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-12"><label class="form-label">Notas</label><textarea class="form-control" name="notes"></textarea></div>
            <div class="col-12 d-flex justify-content-end"><button class="btn btn-primary">Guardar pendiente</button></div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\stock_adjustments\create.blade.php ENDPATH**/ ?>