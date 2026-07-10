<?php $__env->startSection('title', 'Valores de '.$attribute->name); ?>
<?php $__env->startSection('page-title', 'Valores de atributo'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0"><?php echo e($attribute->name); ?></h1>
        <a class="btn btn-outline-secondary" href="<?php echo e(route('admin.attributes.index')); ?>">Volver</a>
    </div>
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold"><?php echo e($editingValue ? 'Editar valor' : 'Crear valor'); ?></div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e($editingValue ? route('admin.attributes.values.update', [$attribute, $editingValue]) : route('admin.attributes.values.store', $attribute)); ?>" class="row g-3">
                        <?php echo csrf_field(); ?>
                        <?php if($editingValue): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
                        <div class="col-12"><label class="form-label" for="value">Valor</label><input class="form-control" id="value" name="value" value="<?php echo e(old('value', $editingValue->value ?? '')); ?>" required></div>
                        <div class="col-12"><label class="form-label" for="slug">Slug</label><input class="form-control" id="slug" name="slug" value="<?php echo e(old('slug', $editingValue->slug ?? '')); ?>" placeholder="Se genera automáticamente"></div>
                        <div class="col-12"><label class="form-label" for="color_hex">Color HEX</label><input class="form-control" id="color_hex" name="color_hex" value="<?php echo e(old('color_hex', $editingValue->color_hex ?? '')); ?>" placeholder="#000000"></div>
                        <div class="col-12"><label class="form-label" for="sort_order">Orden</label><input class="form-control" id="sort_order" name="sort_order" type="number" min="0" value="<?php echo e(old('sort_order', $editingValue->sort_order ?? 0)); ?>"></div>
                        <div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?php if(old('is_active', $editingValue->is_active ?? true)): echo 'checked'; endif; ?>><label class="form-check-label" for="is_active">Activo</label></div></div>
                        <div class="col-12"><button class="btn btn-primary w-100">Guardar valor</button></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead><tr><th>Valor</th><th>Slug</th><th>Color</th><th>Orden</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $attribute->values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($value->value); ?></td><td><?php echo e($value->slug); ?></td><td><?php echo e($value->color_hex ?? '-'); ?></td><td><?php echo e($value->sort_order); ?></td>
                                    <td><span class="badge <?php echo e($value->is_active ? 'text-bg-success' : 'text-bg-secondary'); ?>"><?php echo e($value->is_active ? 'Activo' : 'Inactivo'); ?></span></td>
                                    <td class="text-end"><div class="btn-group">
                                        <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.attributes.values.edit', [$attribute, $value])); ?>"><i class="bi bi-pencil"></i></a>
                                        <form method="POST" action="<?php echo e(route('admin.attributes.values.toggle-active', [$attribute, $value])); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><button class="btn btn-sm btn-outline-warning"><i class="bi bi-person-dash"></i></button></form>
                                    </div></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="6" class="text-center text-secondary py-4">No hay valores.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\attributes\show.blade.php ENDPATH**/ ?>