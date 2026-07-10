<?php $__env->startSection('title', $address->exists ? 'Editar dirección' : 'Nueva dirección'); ?>
<?php $__env->startSection('page-title', $address->exists ? 'Editar dirección' : 'Nueva dirección'); ?>

<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e($address->exists ? route('admin.customers.addresses.update', [$customer, $address]) : route('admin.customers.addresses.store', $customer)); ?>" class="card border-0 shadow-sm">
        <?php echo csrf_field(); ?>
        <?php if($address->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
        <div class="card-body row g-3">
            <div class="col-md-3"><label class="form-label">Tipo</label><select name="address_type" class="form-select"><option value="billing" <?php if(old('address_type', $address->address_type)==='billing'): echo 'selected'; endif; ?>>Facturación</option><option value="shipping" <?php if(old('address_type', $address->address_type)==='shipping'): echo 'selected'; endif; ?>>Despacho</option><option value="other" <?php if(old('address_type', $address->address_type)==='other'): echo 'selected'; endif; ?>>Otra</option></select></div>
            <div class="col-md-3"><label class="form-label">Etiqueta</label><select name="address_label" class="form-select"><option value="main" <?php if(old('address_label', $address->address_label)==='main'): echo 'selected'; endif; ?>>Principal</option><option value="office" <?php if(old('address_label', $address->address_label)==='office'): echo 'selected'; endif; ?>>Oficina</option><option value="home" <?php if(old('address_label', $address->address_label)==='home'): echo 'selected'; endif; ?>>Casa</option><option value="pickup" <?php if(old('address_label', $address->address_label)==='pickup'): echo 'selected'; endif; ?>>Retiro</option></select></div>
            <div class="col-md-3"><label class="form-label">Contacto</label><input name="contact_name" class="form-control" value="<?php echo e(old('contact_name', $address->contact_name ?: $customer->display_name)); ?>" required></div>
            <div class="col-md-3"><label class="form-label">Teléfono</label><input name="phone" class="form-control" value="<?php echo e(old('phone', $address->phone ?: $customer->phone)); ?>"></div>
            <?php $__currentLoopData = ['country' => 'País', 'region' => 'Región', 'province' => 'Provincia', 'commune' => 'Comuna', 'city' => 'Ciudad', 'street' => 'Calle', 'number' => 'Número', 'apartment' => 'Depto/oficina', 'postal_code' => 'Código postal']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-<?php echo e(in_array($field, ['street']) ? 6 : 3); ?>"><label class="form-label"><?php echo e($label); ?></label><input name="<?php echo e($field); ?>" class="form-control" value="<?php echo e(old($field, $address->{$field} ?: ($field === 'country' ? 'Chile' : ''))); ?>" <?php if(! in_array($field, ['province', 'apartment', 'postal_code'])): echo 'required'; endif; ?>></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-3"><label class="form-label">Latitud</label><input type="number" step="0.0000001" name="latitude" class="form-control" value="<?php echo e(old('latitude', $address->latitude)); ?>"></div>
            <div class="col-md-3"><label class="form-label">Longitud</label><input type="number" step="0.0000001" name="longitude" class="form-control" value="<?php echo e(old('longitude', $address->longitude)); ?>"></div>
            <div class="col-12"><label class="form-label">Referencia</label><textarea name="reference" class="form-control"><?php echo e(old('reference', $address->reference)); ?></textarea></div>
            <div class="col-12 d-flex gap-3"><label class="form-check"><input type="checkbox" name="is_default" value="1" class="form-check-input" <?php if(old('is_default', $address->is_default)): echo 'checked'; endif; ?>> Dirección por defecto</label><label class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" <?php if(old('is_active', $address->exists ? $address->is_active : true)): echo 'checked'; endif; ?>> Activa</label></div>
            <?php if($errors->any()): ?><div class="col-12"><div class="alert alert-danger"><?php echo e($errors->first()); ?></div></div><?php endif; ?>
        </div>
        <div class="card-footer bg-white text-end"><a href="<?php echo e(route('admin.customers.show', $customer)); ?>" class="btn btn-outline-secondary">Cancelar</a> <button class="btn btn-dark">Guardar</button></div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\customers\addresses\form.blade.php ENDPATH**/ ?>