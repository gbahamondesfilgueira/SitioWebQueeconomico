<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Datos de variante</div>
            <div class="card-body row g-3">
                <div class="col-md-4"><label class="form-label" for="sku">SKU</label><input class="form-control <?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="sku" name="sku" value="<?php echo e(old('sku', $variant->sku)); ?>"><?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                <div class="col-md-4"><label class="form-label" for="barcode">Código de barras</label><input class="form-control <?php $__errorArgs = ['barcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="barcode" name="barcode" value="<?php echo e(old('barcode', $variant->barcode)); ?>"><?php $__errorArgs = ['barcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                <div class="col-md-4"><label class="form-label" for="name">Nombre interno</label><input class="form-control" id="name" name="name" value="<?php echo e(old('name', $variant->name)); ?>"></div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Atributos</div>
            <div class="card-body row g-3">
                <?php $selectedValues = collect(old('attribute_value_ids', $variant->attributeValues->pluck('id')->all())); ?>
                <?php $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6">
                        <label class="form-label"><?php echo e($attribute->name); ?></label>
                        <select class="form-select" name="attribute_value_ids[]">
                            <option value="">Sin valor</option>
                            <?php $__currentLoopData = $attribute->values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value->id); ?>" <?php if($selectedValues->contains($value->id)): echo 'selected'; endif; ?>><?php echo e($value->value); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php $__errorArgs = ['attribute_value_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Precios y oferta</div>
            <div class="card-body row g-3">
                <?php $__currentLoopData = [['cost_price','Costo'], ['regular_price','Precio normal'], ['sale_price','Precio oferta']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$field, $label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-4"><label class="form-label" for="<?php echo e($field); ?>"><?php echo e($label); ?></label><input class="form-control <?php $__errorArgs = [$field];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="<?php echo e($field); ?>" name="<?php echo e($field); ?>" type="number" min="0" step="0.01" value="<?php echo e(old($field, $variant->{$field})); ?>"><?php $__errorArgs = [$field];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6"><label class="form-label" for="sale_starts_at">Inicio oferta</label><input class="form-control" id="sale_starts_at" name="sale_starts_at" type="datetime-local" value="<?php echo e(old('sale_starts_at', optional($variant->sale_starts_at)->format('Y-m-d\TH:i'))); ?>"></div>
                <div class="col-md-6"><label class="form-label" for="sale_ends_at">Término oferta</label><input class="form-control" id="sale_ends_at" name="sale_ends_at" type="datetime-local" value="<?php echo e(old('sale_ends_at', optional($variant->sale_ends_at)->format('Y-m-d\TH:i'))); ?>"></div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Peso y medidas</div>
            <div class="card-body row g-3">
                <?php $__currentLoopData = [['weight','Peso'], ['height','Alto'], ['width','Ancho'], ['length','Largo']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$field, $label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-3"><label class="form-label" for="<?php echo e($field); ?>"><?php echo e($label); ?></label><input class="form-control" id="<?php echo e($field); ?>" name="<?php echo e($field); ?>" type="number" min="0" step="0.001" value="<?php echo e(old($field, $variant->{$field})); ?>"></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6"><label class="form-label" for="weight_unit_id">Unidad peso</label><select class="form-select" id="weight_unit_id" name="weight_unit_id"><option value="">Sin unidad</option><?php $__currentLoopData = $weightUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($unit->id); ?>" <?php if(old('weight_unit_id', $variant->weight_unit_id) == $unit->id): echo 'selected'; endif; ?>><?php echo e($unit->name); ?> (<?php echo e($unit->code); ?>)</option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                <div class="col-md-6"><label class="form-label" for="dimension_unit_id">Unidad dimensión</label><select class="form-select" id="dimension_unit_id" name="dimension_unit_id"><option value="">Sin unidad</option><?php $__currentLoopData = $dimensionUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($unit->id); ?>" <?php if(old('dimension_unit_id', $variant->dimension_unit_id) == $unit->id): echo 'selected'; endif; ?>><?php echo e($unit->name); ?> (<?php echo e($unit->code); ?>)</option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Estado e imagen</div>
            <div class="card-body">
                <div class="form-check form-switch mb-3"><input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" <?php if(old('is_active', $variant->exists ? $variant->is_active : true)): echo 'checked'; endif; ?>><label class="form-check-label" for="is_active">Activo</label></div>
                <label class="form-label" for="image">Imagen variante</label>
                <input class="form-control" id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp">
                <?php if($variant->image_path): ?><img src="<?php echo e(asset('storage/'.$variant->image_path)); ?>" class="img-fluid rounded mt-3" alt="Variante"><?php endif; ?>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button class="btn btn-primary">Guardar variante</button>
            <a class="btn btn-outline-secondary" href="<?php echo e(route('admin.products.variants.index', $product)); ?>">Cancelar</a>
        </div>
    </div>
</div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\products\variants\partials\form.blade.php ENDPATH**/ ?>