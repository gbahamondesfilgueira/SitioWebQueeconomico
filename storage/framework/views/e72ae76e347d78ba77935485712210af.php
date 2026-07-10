<form method="POST" action="<?php echo e($action); ?>" class="card">
    <?php echo csrf_field(); ?>
    <?php if($method !== 'POST'): ?> <?php echo method_field($method); ?> <?php endif; ?>
    <div class="card-body row g-3">
        <div class="col-md-6"><label class="form-label">Nombre</label><input name="name" value="<?php echo e(old('name', $terminal?->name)); ?>" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label">Código</label><input name="code" value="<?php echo e(old('code', $terminal?->code)); ?>" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label">Bodega</label><select name="warehouse_id" class="form-select" required><?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($warehouse->id); ?>" <?php if(old('warehouse_id', $terminal?->warehouse_id) == $warehouse->id): echo 'selected'; endif; ?>><?php echo e($warehouse->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <div class="col-md-6"><label class="form-label">Ubicación</label><select name="location_id" class="form-select"><option value="">Sin ubicación específica</option><?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($location->id); ?>" <?php if(old('location_id', $terminal?->location_id) == $location->id): echo 'selected'; endif; ?>><?php echo e($location->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <div class="col-12"><label class="form-label">Descripción</label><textarea name="description" class="form-control"><?php echo e(old('description', $terminal?->description)); ?></textarea></div>
        <div class="col-12"><label class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" <?php if(old('is_active', $terminal?->is_active ?? true)): echo 'checked'; endif; ?>> Activo</label></div>
    </div>
    <div class="card-footer text-end"><button class="btn btn-primary">Guardar</button></div>
</form>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\pos\terminals\form.blade.php ENDPATH**/ ?>