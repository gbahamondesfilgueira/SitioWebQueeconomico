<?php echo csrf_field(); ?>
<div class="row g-3">
    <div class="col-md-4"><label class="form-label">Nombre</label><input name="name" value="<?php echo e(old('name', $currency->name)); ?>" class="form-control" required></div>
    <div class="col-md-2"><label class="form-label">Código</label><input name="code" value="<?php echo e(old('code', $currency->code)); ?>" class="form-control" required></div>
    <div class="col-md-2"><label class="form-label">Símbolo</label><input name="symbol" value="<?php echo e(old('symbol', $currency->symbol)); ?>" class="form-control" required></div>
    <div class="col-md-2"><label class="form-label">Decimales</label><input type="number" name="decimal_places" value="<?php echo e(old('decimal_places', $currency->decimal_places ?? 0)); ?>" class="form-control" required></div>
    <div class="col-md-2 pt-4">
        <label class="form-check"><input type="checkbox" name="is_default" value="1" class="form-check-input" <?php if(old('is_default', $currency->is_default)): echo 'checked'; endif; ?>> Default</label>
        <label class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" <?php if(old('is_active', $currency->is_active ?? true)): echo 'checked'; endif; ?>> Activa</label>
    </div>
    <div class="col-12"><button class="btn btn-dark">Guardar</button> <a href="<?php echo e(route('admin.currencies.index')); ?>" class="btn btn-outline-secondary">Volver</a></div>
</div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\currencies\form.blade.php ENDPATH**/ ?>