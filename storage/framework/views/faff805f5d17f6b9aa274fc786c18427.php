<?php $__env->startSection('title', 'Mis Empresas'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white fw-semibold">Agregar empresa para facturacion</div>
        <form method="POST" action="<?php echo e(route('account.companies.store')); ?>" class="card-body row g-3">
            <?php echo csrf_field(); ?>
            <div class="col-md-6"><label class="form-label">Razon social</label><input name="company_name" class="form-control" value="<?php echo e(old('company_name')); ?>" required></div>
            <div class="col-md-3"><label class="form-label">RUT</label><input name="rut" class="form-control" value="<?php echo e(old('rut')); ?>" required></div>
            <div class="col-md-3"><label class="form-label">Giro</label><input name="business_activity" class="form-control" value="<?php echo e(old('business_activity')); ?>" required></div>
            <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?php echo e(old('email', $customer->email)); ?>" required></div>
            <div class="col-md-4"><label class="form-label">Telefono</label><input name="phone" class="form-control" value="<?php echo e(old('phone', $customer->phone)); ?>" required></div>
            <div class="col-md-4"><label class="form-label">Email facturacion</label><input type="email" name="billing_email" class="form-control" value="<?php echo e(old('billing_email')); ?>"></div>
            <div class="col-md-6"><label class="form-label">Sitio web</label><input type="url" name="website" class="form-control" value="<?php echo e(old('website')); ?>"></div>
            <div class="col-md-3"><label class="form-label">Condiciones de pago</label><input name="payment_terms" class="form-control" value="<?php echo e(old('payment_terms')); ?>"></div>
            <div class="col-md-3"><label class="form-label">Credito</label><input type="number" min="0" step="1" name="credit_limit" class="form-control" value="<?php echo e(old('credit_limit')); ?>"></div>
            <div class="col-12"><button class="btn btn-dark">Guardar empresa</button></div>
        </form>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold">Mis Empresas</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $customer->companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($company->company_name); ?></td>
                            <td><?php echo e($company->rut); ?></td>
                            <td><?php echo e($company->billing_email ?: $company->email); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td class="text-secondary">Todavia no tienes empresas registradas.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.account', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\account\companies.blade.php ENDPATH**/ ?>