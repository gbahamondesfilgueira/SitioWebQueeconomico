<div class="col-md-6"><label class="form-label" for="name">Nombre</label><input class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" value="<?php echo e(old('name', $attribute->name)); ?>" required><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
<div class="col-md-6"><label class="form-label" for="slug">Slug</label><input class="form-control <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="slug" name="slug" value="<?php echo e(old('slug', $attribute->slug)); ?>" placeholder="Se genera automáticamente"><?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
<div class="col-md-6"><label class="form-label" for="type">Tipo</label><select class="form-select" id="type" name="type"><?php $__currentLoopData = \App\Models\Attribute::TYPES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($type); ?>" <?php if(old('type', $attribute->type ?: 'select') === $type): echo 'selected'; endif; ?>><?php echo e($type); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
<div class="col-md-6"><label class="form-label" for="sort_order">Orden</label><input class="form-control" id="sort_order" name="sort_order" type="number" min="0" value="<?php echo e(old('sort_order', $attribute->sort_order ?? 0)); ?>"></div>
<div class="col-12"><div class="form-check form-switch"><input class="form-check-input" id="is_active" name="is_active" value="1" type="checkbox" <?php if(old('is_active', $attribute->exists ? $attribute->is_active : true)): echo 'checked'; endif; ?>><label class="form-check-label" for="is_active">Activo</label></div></div>
<div class="col-12 d-flex justify-content-end gap-2"><a class="btn btn-outline-secondary" href="<?php echo e(route('admin.attributes.index')); ?>">Cancelar</a><button class="btn btn-primary">Guardar</button></div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\attributes\partials\form.blade.php ENDPATH**/ ?>