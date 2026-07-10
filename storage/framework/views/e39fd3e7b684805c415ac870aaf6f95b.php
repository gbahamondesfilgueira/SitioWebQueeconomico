<?php echo csrf_field(); ?>
<div class="row g-3">
    <div class="col-md-4"><label class="form-label">Empresa</label><select name="company_id" class="form-select"><option value="">Sin empresa</option><?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($company->id); ?>" <?php if(old('company_id', $branch->company_id) == $company->id): echo 'selected'; endif; ?>><?php echo e($company->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
    <div class="col-md-4"><label class="form-label">Nombre</label><input name="name" value="<?php echo e(old('name', $branch->name)); ?>" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Código</label><input name="code" value="<?php echo e(old('code', $branch->code)); ?>" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Dirección</label><input name="address" value="<?php echo e(old('address', $branch->address)); ?>" class="form-control"></div>
    <div class="col-md-3"><label class="form-label">Región</label><input name="region" value="<?php echo e(old('region', $branch->region)); ?>" class="form-control"></div>
    <div class="col-md-3"><label class="form-label">Comuna</label><input name="commune" value="<?php echo e(old('commune', $branch->commune)); ?>" class="form-control"></div>
    <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" value="<?php echo e(old('email', $branch->email)); ?>" class="form-control"></div>
    <div class="col-md-6"><label class="form-label">Teléfono</label><input name="phone" value="<?php echo e(old('phone', $branch->phone)); ?>" class="form-control"></div>
    <div class="col-12"><label class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" <?php if(old('is_active', $branch->is_active ?? true)): echo 'checked'; endif; ?>> Activa</label></div>
    <div class="col-12"><button class="btn btn-dark">Guardar</button> <a href="<?php echo e(route('admin.branches.index')); ?>" class="btn btn-outline-secondary">Volver</a></div>
</div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\branches\form.blade.php ENDPATH**/ ?>