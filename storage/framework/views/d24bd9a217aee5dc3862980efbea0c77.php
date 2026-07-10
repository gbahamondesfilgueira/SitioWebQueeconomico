<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['type' => 'shipping', 'savedAddresses' => collect()]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['type' => 'shipping', 'savedAddresses' => collect()]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if($savedAddresses->isNotEmpty()): ?>
    <label class="form-label">Usar direccion guardada</label>
    <select name="customer_address_id" class="form-select mb-3" data-address-select>
        <option value="">Ingresar nueva direccion</option>
        <?php $__currentLoopData = $savedAddresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option
                value="<?php echo e($address->id); ?>"
                data-address='<?php echo json_encode([
                    "contact_name" => $address->contact_name, "phone" => $address->phone, "email" => $address->customerProfile?->email) ?>'
            >
                <?php echo e($address->address_type); ?> · <?php echo e($address->street); ?> <?php echo e($address->number); ?>, <?php echo e($address->commune); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
<?php endif; ?>

<div class="row g-3" data-address-fields>
    <div class="col-md-6"><label class="form-label">Nombre contacto</label><input name="contact_name" class="form-control" value="<?php echo e(old('contact_name')); ?>"></div>
    <div class="col-md-3"><label class="form-label">Telefono</label><input name="phone" class="form-control" value="<?php echo e(old('phone')); ?>"></div>
    <div class="col-md-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>"></div>
    <div class="col-md-3"><label class="form-label">Pais</label><input name="country" class="form-control" value="<?php echo e(old('country', 'Chile')); ?>"></div>
    <div class="col-md-3"><label class="form-label">Region</label><input name="region" class="form-control" value="<?php echo e(old('region')); ?>"></div>
    <div class="col-md-3"><label class="form-label">Comuna</label><input name="commune" class="form-control" value="<?php echo e(old('commune')); ?>"></div>
    <div class="col-md-3"><label class="form-label">Ciudad</label><input name="city" class="form-control" value="<?php echo e(old('city')); ?>"></div>
    <div class="col-md-6"><label class="form-label">Calle</label><input name="street" class="form-control" value="<?php echo e(old('street')); ?>"></div>
    <div class="col-md-2"><label class="form-label">Numero</label><input name="number" class="form-control" value="<?php echo e(old('number')); ?>"></div>
    <div class="col-md-2"><label class="form-label">Depto</label><input name="apartment" class="form-control" value="<?php echo e(old('apartment')); ?>"></div>
    <div class="col-md-2"><label class="form-label">Codigo postal</label><input name="postal_code" class="form-control" value="<?php echo e(old('postal_code')); ?>"></div>
    <div class="col-12"><label class="form-label">Referencia</label><textarea name="reference" class="form-control"><?php echo e(old('reference')); ?></textarea></div>
</div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\components\store\address-form.blade.php ENDPATH**/ ?>