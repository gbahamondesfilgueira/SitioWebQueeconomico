<?php $__currentLoopData = $config['fields']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $name = $field['name'];
        $type = $field['type'] ?? 'text';
        $value = old($name, $item->{$name});
    ?>

    <?php if($type === 'checkbox'): ?>
        <div class="col-12">
            <div class="form-check form-switch">
                <input class="form-check-input <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="checkbox" role="switch" id="<?php echo e($name); ?>" name="<?php echo e($name); ?>" value="1" <?php if(old($name, $item->{$name})): echo 'checked'; endif; ?>>
                <label class="form-check-label" for="<?php echo e($name); ?>"><?php echo e($field['label']); ?></label>
                <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
    <?php else: ?>
        <div class="<?php echo e($type === 'textarea' ? 'col-12' : 'col-md-6'); ?>">
            <label class="form-label" for="<?php echo e($name); ?>"><?php echo e($field['label']); ?></label>

            <?php if($type === 'textarea'): ?>
                <textarea class="form-control <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="<?php echo e($name); ?>" name="<?php echo e($name); ?>" rows="4"><?php echo e($value); ?></textarea>
            <?php elseif($type === 'select'): ?>
                <select class="form-select <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="<?php echo e($name); ?>" name="<?php echo e($name); ?>">
                    <option value="">Selecciona una opción</option>
                    <?php $__currentLoopData = $config['typeOptions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionValue => $optionLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($optionValue); ?>" <?php if($value === $optionValue): echo 'selected'; endif; ?>><?php echo e($optionLabel); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            <?php else: ?>
                <input class="form-control <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="<?php echo e($name); ?>" name="<?php echo e($name); ?>" type="<?php echo e($type); ?>" value="<?php echo e($value); ?>" <?php if($type === 'number'): ?> step="0.01" <?php endif; ?>>
            <?php endif; ?>

            <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php if($config['hasActiveToggle']): ?>
    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" <?php if(old('is_active', $item->exists ? $item->is_active : true)): echo 'checked'; endif; ?>>
            <label class="form-check-label" for="is_active">Activo</label>
        </div>
    </div>
<?php endif; ?>

<div class="col-12 d-flex justify-content-end gap-2">
    <a class="btn btn-outline-secondary" href="<?php echo e(route($config['routePrefix'].'.index')); ?>">Cancelar</a>
    <button class="btn btn-primary" type="submit">Guardar</button>
</div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\catalog\partials\form.blade.php ENDPATH**/ ?>