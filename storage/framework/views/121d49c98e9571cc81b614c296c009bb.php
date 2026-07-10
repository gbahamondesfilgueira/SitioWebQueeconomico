<?php $__env->startSection('title', $title); ?>
<?php $__env->startSection('page-title', $title); ?>
<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e($item->exists ? route('admin.shipping.'.$route.'.update', $item) : route('admin.shipping.'.$route.'.store')); ?>" class="card border-0 shadow-sm">
        <?php echo csrf_field(); ?> <?php if($item->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
        <div class="card-body row g-3">
            <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-<?php echo e($type === 'textarea' ? 12 : 4); ?>">
                    <?php if($type === 'checkbox'): ?>
                        <label class="form-check mt-4"><input type="checkbox" name="<?php echo e($field); ?>" value="1" class="form-check-input" <?php if(old($field, $item->{$field} ?? true)): echo 'checked'; endif; ?>> <?php echo e(str_replace('_', ' ', ucfirst($field))); ?></label>
                    <?php elseif(str_starts_with($type, 'select:')): ?>
                        <?php ($collection = ${str_replace('select:', '', $type)}); ?>
                        <label class="form-label"><?php echo e(str_replace('_', ' ', ucfirst($field))); ?></label><select name="<?php echo e($field); ?>" class="form-select"><option value="">Seleccionar</option><?php $__currentLoopData = $collection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionKey => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(is_object($option)): ?><option value="<?php echo e($option->id); ?>" <?php if(old($field, $item->{$field}) == $option->id): echo 'selected'; endif; ?>><?php echo e($option->name); ?></option><?php else: ?><option value="<?php echo e($optionKey); ?>" <?php if(old($field, $item->{$field}) == $optionKey): echo 'selected'; endif; ?>><?php echo e($option); ?></option><?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
                    <?php elseif($type === 'textarea'): ?>
                        <label class="form-label"><?php echo e(str_replace('_', ' ', ucfirst($field))); ?></label><textarea name="<?php echo e($field); ?>" class="form-control"><?php echo e(old($field, $item->{$field})); ?></textarea>
                    <?php else: ?>
                        <label class="form-label"><?php echo e(str_replace('_', ' ', ucfirst($field))); ?></label><input type="<?php echo e($type); ?>" step="0.001" name="<?php echo e($field); ?>" class="form-control" value="<?php echo e(old($field, $item->{$field})); ?>">
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($errors->any()): ?><div class="col-12"><div class="alert alert-danger"><?php echo e($errors->first()); ?></div></div><?php endif; ?>
        </div>
        <div class="card-footer bg-white text-end"><a href="<?php echo e(route('admin.shipping.'.$route.'.index')); ?>" class="btn btn-outline-secondary">Volver</a> <button class="btn btn-dark">Guardar</button></div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\shipping\shared\form.blade.php ENDPATH**/ ?>