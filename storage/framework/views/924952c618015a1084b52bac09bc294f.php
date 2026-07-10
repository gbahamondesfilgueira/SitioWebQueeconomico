<?php ($selectedTags = old('tag_ids', $customer->exists ? $customer->tags->pluck('id')->all() : [])); ?>
<div class="row g-3">
    <div class="col-md-3">
        <label class="form-label">Tipo de cliente</label>
        <select name="customer_type" class="form-select">
            <option value="individual" <?php if(old('customer_type', $customer->customer_type) === 'individual'): echo 'selected'; endif; ?>>Particular</option>
            <option value="company" <?php if(old('customer_type', $customer->customer_type) === 'company'): echo 'selected'; endif; ?>>Empresa</option>
        </select>
    </div>
    <div class="col-md-3"><label class="form-label">Nombre</label><input name="first_name" class="form-control" value="<?php echo e(old('first_name', $customer->first_name)); ?>" required></div>
    <div class="col-md-3"><label class="form-label">Apellido</label><input name="last_name" class="form-control" value="<?php echo e(old('last_name', $customer->last_name)); ?>" required></div>
    <div class="col-md-3"><label class="form-label">RUT</label><input name="rut" class="form-control" value="<?php echo e(old('rut', $customer->rut)); ?>"></div>
    <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?php echo e(old('email', $customer->email)); ?>" required></div>
    <div class="col-md-4"><label class="form-label">Teléfono</label><input name="phone" class="form-control" value="<?php echo e(old('phone', $customer->phone)); ?>"></div>
    <div class="col-md-4"><label class="form-label">Móvil</label><input name="mobile" class="form-control" value="<?php echo e(old('mobile', $customer->mobile)); ?>"></div>
    <div class="col-md-4"><label class="form-label">Empresa</label><input name="company_name" class="form-control" value="<?php echo e(old('company_name', $customer->company_name)); ?>"></div>
    <div class="col-md-4"><label class="form-label">Giro</label><input name="business_activity" class="form-control" value="<?php echo e(old('business_activity', $customer->business_activity)); ?>"></div>
    <div class="col-md-2"><label class="form-label">Nacimiento</label><input type="date" name="birth_date" class="form-control" value="<?php echo e(old('birth_date', optional($customer->birth_date)->format('Y-m-d'))); ?>"></div>
    <div class="col-md-2"><label class="form-label">Género</label><input name="gender" class="form-control" value="<?php echo e(old('gender', $customer->gender)); ?>"></div>
    <div class="col-md-4">
        <label class="form-label">Lista de precios preferida</label>
        <select name="preferred_price_list_id" class="form-select">
            <option value="">Sin preferencia</option>
            <?php $__currentLoopData = $priceLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($list->id); ?>" <?php if((int) old('preferred_price_list_id', $customer->preferred_price_list_id) === $list->id): echo 'selected'; endif; ?>><?php echo e($list->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Etiquetas</label>
        <select name="tag_ids[]" class="form-select" multiple>
            <?php $__currentLoopData = $customerTags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($tag->id); ?>" <?php if(in_array($tag->id, $selectedTags)): echo 'selected'; endif; ?>><?php echo e($tag->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <?php if (! ($customer->exists)): ?>
        <div class="col-md-4"><label class="form-label">Contraseña inicial</label><input type="password" name="password" class="form-control"></div>
        <div class="col-md-4"><label class="form-label">Confirmar contraseña</label><input type="password" name="password_confirmation" class="form-control"></div>
    <?php endif; ?>
    <div class="col-12"><label class="form-label">Notas internas</label><textarea name="notes" class="form-control" rows="3"><?php echo e(old('notes', $customer->notes)); ?></textarea></div>
    <div class="col-12">
        <div class="d-flex flex-wrap gap-3">
            <?php $__currentLoopData = ['is_active' => 'Activo', 'newsletter' => 'Newsletter', 'accept_promotions' => 'Promociones', 'accept_sms' => 'SMS', 'accept_whatsapp' => 'WhatsApp', 'accept_email_marketing' => 'Email marketing', 'accept_cookies' => 'Cookies']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <label class="form-check"><input type="checkbox" name="<?php echo e($field); ?>" value="1" class="form-check-input" <?php if(old($field, $customer->exists ? $customer->{$field} : $field === 'is_active')): echo 'checked'; endif; ?>> <?php echo e($label); ?></label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php if($errors->any()): ?>
    <div class="alert alert-danger mt-3 mb-0"><?php echo e($errors->first()); ?></div>
<?php endif; ?>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\customers\partials\form.blade.php ENDPATH**/ ?>