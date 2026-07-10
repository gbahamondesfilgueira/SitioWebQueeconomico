<?php $__env->startSection('title', $company->exists ? 'Editar empresa' : 'Nueva empresa'); ?>
<?php $__env->startSection('page-title', $company->exists ? 'Editar empresa' : 'Nueva empresa'); ?>

<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e($company->exists ? route('admin.customers.companies.update', [$customer, $company]) : route('admin.customers.companies.store', $customer)); ?>" class="card border-0 shadow-sm">
        <?php echo csrf_field(); ?>
        <?php if($company->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
        <div class="card-body row g-3">
            <div class="col-md-6"><label class="form-label">Razón social</label><input name="company_name" class="form-control" value="<?php echo e(old('company_name', $company->company_name)); ?>" required></div>
            <div class="col-md-3"><label class="form-label">RUT</label><input name="rut" class="form-control" value="<?php echo e(old('rut', $company->rut)); ?>" required></div>
            <div class="col-md-3"><label class="form-label">Giro</label><input name="business_activity" class="form-control" value="<?php echo e(old('business_activity', $company->business_activity)); ?>" required></div>
            <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?php echo e(old('email', $company->email)); ?>" required></div>
            <div class="col-md-4"><label class="form-label">Teléfono</label><input name="phone" class="form-control" value="<?php echo e(old('phone', $company->phone)); ?>"></div>
            <div class="col-md-4"><label class="form-label">Sitio web</label><input type="url" name="website" class="form-control" value="<?php echo e(old('website', $company->website)); ?>"></div>
            <div class="col-md-4"><label class="form-label">Email facturación</label><input type="email" name="billing_email" class="form-control" value="<?php echo e(old('billing_email', $company->billing_email)); ?>"></div>
            <div class="col-md-4"><label class="form-label">Condición de pago</label><input name="payment_terms" class="form-control" value="<?php echo e(old('payment_terms', $company->payment_terms)); ?>"></div>
            <div class="col-md-4"><label class="form-label">Límite crédito</label><input type="number" step="0.01" name="credit_limit" class="form-control" value="<?php echo e(old('credit_limit', $company->credit_limit)); ?>"></div>
            <div class="col-12"><label class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" <?php if(old('is_active', $company->exists ? $company->is_active : true)): echo 'checked'; endif; ?>> Activa</label></div>
            <?php if($errors->any()): ?><div class="col-12"><div class="alert alert-danger"><?php echo e($errors->first()); ?></div></div><?php endif; ?>
        </div>
        <div class="card-footer bg-white text-end"><a href="<?php echo e(route('admin.customers.show', $customer)); ?>" class="btn btn-outline-secondary">Cancelar</a> <button class="btn btn-dark">Guardar</button></div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\customers\companies\form.blade.php ENDPATH**/ ?>