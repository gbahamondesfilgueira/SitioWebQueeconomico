<?php $__env->startSection('title', 'Mi Perfil'); ?>

<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e(route('account.profile.update')); ?>" class="card border-0 shadow-sm">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="card-header bg-white fw-semibold">Mi Perfil</div>
        <div class="card-body row g-3">
            <div class="col-md-6"><label class="form-label">Nombre</label><input name="first_name" class="form-control" value="<?php echo e(old('first_name', $customer->first_name)); ?>" required></div>
            <div class="col-md-6"><label class="form-label">Apellido</label><input name="last_name" class="form-control" value="<?php echo e(old('last_name', $customer->last_name)); ?>" required></div>
            <div class="col-md-4"><label class="form-label">Teléfono</label><input name="phone" class="form-control" value="<?php echo e(old('phone', $customer->phone)); ?>"></div>
            <div class="col-md-4"><label class="form-label">Móvil</label><input name="mobile" class="form-control" value="<?php echo e(old('mobile', $customer->mobile)); ?>"></div>
            <div class="col-md-4"><label class="form-label">Nacimiento</label><input type="date" name="birth_date" class="form-control" value="<?php echo e(old('birth_date', optional($customer->birth_date)->format('Y-m-d'))); ?>"></div>
            <div class="col-md-4"><label class="form-label">Género</label><input name="gender" class="form-control" value="<?php echo e(old('gender', $customer->gender)); ?>"></div>
            <div class="col-12 d-flex flex-wrap gap-3">
                <?php $__currentLoopData = ['newsletter' => 'Newsletter', 'accept_promotions' => 'Promociones', 'accept_sms' => 'SMS', 'accept_whatsapp' => 'WhatsApp', 'accept_email_marketing' => 'Email marketing', 'accept_cookies' => 'Cookies']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="form-check"><input type="checkbox" name="<?php echo e($field); ?>" value="1" class="form-check-input" <?php if(old($field, $customer->{$field})): echo 'checked'; endif; ?>> <?php echo e($label); ?></label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php if($errors->any()): ?><div class="col-12"><div class="alert alert-danger"><?php echo e($errors->first()); ?></div></div><?php endif; ?>
        </div>
        <div class="card-footer bg-white text-end"><button class="btn btn-dark">Guardar</button></div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.account', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\account\profile.blade.php ENDPATH**/ ?>