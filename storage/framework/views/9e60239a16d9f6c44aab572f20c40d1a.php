<?php $__env->startSection('title', 'Crear transferencia'); ?>
<?php $__env->startSection('page-title', 'Crear transferencia'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('admin.stock-transfers.store')); ?>" class="row g-3">
                <?php echo csrf_field(); ?>

                <div class="col-md-6">
                    <label class="form-label">Bodega origen</label>
                    <select class="form-select" name="origin_warehouse_id" required>
                        <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($warehouse->id); ?>"><?php echo e($warehouse->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Bodega destino</label>
                    <select class="form-select" name="destination_warehouse_id" required>
                        <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($warehouse->id); ?>"><?php echo e($warehouse->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Bodega / ubicacion de origen</label>
                    <select class="form-select" name="origin_location_id">
                        <option value="">Sin ubicacion especifica</option>
                        <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $warehouse->locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($location->id); ?>"><?php echo e($warehouse->code); ?> / <?php echo e($location->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="form-text">Indica desde que zona fisica saldra el stock.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Bodega / ubicacion de destino</label>
                    <select class="form-select" name="destination_location_id">
                        <option value="">Sin ubicacion especifica</option>
                        <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $warehouse->locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($location->id); ?>"><?php echo e($warehouse->code); ?> / <?php echo e($location->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="form-text">Indica donde quedara fisicamente el stock recibido.</div>
                </div>

                <div class="col-12"><h2 class="h6">Item principal</h2></div>

                <div class="col-md-5">
                    <label class="form-label">Producto</label>
                    <select class="form-select" name="items[0][product_id]" required>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($product->id); ?>"><?php echo e($product->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label">Variante</label>
                    <select class="form-select" name="items[0][product_variant_id]">
                        <option value="">Producto simple</option>
                        <?php $__currentLoopData = $variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($variant->id); ?>"><?php echo e($variant->product->name); ?> / <?php echo e($variant->sku); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Cantidad</label>
                    <input class="form-control" name="items[0][quantity]" type="number" min="1" step="1" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Notas</label>
                    <textarea class="form-control" name="notes"></textarea>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary">Crear transferencia</button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\stock_transfers\create.blade.php ENDPATH**/ ?>