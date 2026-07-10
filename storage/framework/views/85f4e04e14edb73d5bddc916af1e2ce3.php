<?php echo csrf_field(); ?>
<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Nombre</label><input name="name" value="<?php echo e(old('name', $company->name)); ?>" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Nombre legal</label><input name="legal_name" value="<?php echo e(old('legal_name', $company->legal_name)); ?>" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">RUT</label><input name="rut" value="<?php echo e(old('rut', $company->rut)); ?>" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" value="<?php echo e(old('email', $company->email)); ?>" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">Teléfono</label><input name="phone" value="<?php echo e(old('phone', $company->phone)); ?>" class="form-control"></div>
    <div class="col-md-6"><label class="form-label">Dirección</label><input name="address" value="<?php echo e(old('address', $company->address)); ?>" class="form-control"></div>
    <div class="col-md-3"><label class="form-label">Moneda</label><input name="currency" value="<?php echo e(old('currency', $company->currency ?: 'CLP')); ?>" class="form-control" required></div>
    <div class="col-md-3"><label class="form-label">IVA</label><input type="number" step="0.01" name="tax_percentage" value="<?php echo e(old('tax_percentage', $company->tax_percentage ?: 19)); ?>" class="form-control" required></div>
    <div class="col-12"><label class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" <?php if(old('is_active', $company->is_active ?? true)): echo 'checked'; endif; ?>> Activa</label></div>
    <div class="col-12"><button class="btn btn-dark">Guardar</button> <a href="<?php echo e(route('admin.companies.index')); ?>" class="btn btn-outline-secondary">Volver</a></div>
</div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\companies\form.blade.php ENDPATH**/ ?>