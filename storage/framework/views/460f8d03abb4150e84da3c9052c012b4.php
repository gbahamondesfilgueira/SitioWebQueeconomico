<?php $__currentLoopData = $config['fields']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php ($name=$field['name']); ?> <?php ($type=$field['type']); ?>
<?php if($type==='checkbox'): ?>
<div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="<?php echo e($name); ?>" value="1" <?php if(old($name,$item->exists ? $item->{$name} : false)): echo 'checked'; endif; ?>><label class="form-check-label"><?php echo e($field['label']); ?></label></div></div>
<?php elseif($type==='textarea'): ?>
<div class="col-12"><label class="form-label"><?php echo e($field['label']); ?></label><textarea class="form-control" name="<?php echo e($name); ?>" rows="3"><?php echo e(old($name,$item->{$name})); ?></textarea></div>
<?php elseif($type==='select'): ?>
<div class="col-md-6"><label class="form-label"><?php echo e($field['label']); ?></label><select class="form-select" name="<?php echo e($name); ?>"><?php $__currentLoopData = $config['options'][$name] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($option); ?>" <?php if(old($name,$item->{$name})===$option): echo 'selected'; endif; ?>><?php echo e($option); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
<?php elseif(in_array($type,['product','variant','category','brand'])): ?>
<div class="col-md-6"><label class="form-label"><?php echo e($field['label']); ?></label><select class="form-select" name="<?php echo e($name); ?>"><option value="">Sin asignar</option><?php $__currentLoopData = ${$type.'s'} ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($option->id); ?>" <?php if(old($name,$item->{$name})==$option->id): echo 'selected'; endif; ?>><?php echo e($type==='variant' ? ($option->product->name.' / '.$option->sku) : $option->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
<?php else: ?>
<div class="col-md-6"><label class="form-label"><?php echo e($field['label']); ?></label><input class="form-control" name="<?php echo e($name); ?>" type="<?php echo e($type); ?>" step="0.01" value="<?php echo e(old($name, $item->{$name} instanceof \Illuminate\Support\Carbon ? $item->{$name}->format('Y-m-d\TH:i') : $item->{$name})); ?>"></div>
<?php endif; ?>
<?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<div class="col-12 d-flex justify-content-end gap-2"><a class="btn btn-outline-secondary" href="<?php echo e(route($config['routePrefix'].'.index')); ?>">Cancelar</a><button class="btn btn-primary">Guardar</button></div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\commercial\partials\form.blade.php ENDPATH**/ ?>