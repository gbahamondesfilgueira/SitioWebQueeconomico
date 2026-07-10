<?php $__env->startSection('title','Crear API Client'); ?>
<?php $__env->startSection('content'); ?>
<form method="POST" action="<?php echo e(route('admin.api-clients.store')); ?>" class="card"><?php echo csrf_field(); ?>
<div class="card-body row g-3">
<div class="col-md-6"><label class="form-label">Nombre</label><input name="name" class="form-control" required></div>
<div class="col-md-6"><label class="form-label">Código</label><input name="code" class="form-control" required></div>
<div class="col-12"><label class="form-label">Descripción</label><textarea name="description" class="form-control"></textarea></div>
<div class="col-md-4"><label class="form-label">Rate limit/min</label><input name="rate_limit_per_minute" type="number" value="60" class="form-control"></div>
<div class="col-12"><label class="form-label">Permisos</label><select name="permissions[]" class="form-select" multiple><?php $__currentLoopData = ['read_products','write_products','read_stock','write_stock','read_orders','write_orders','read_customers','write_customers','read_shipments','write_shipments']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($perm); ?>"><?php echo e($perm); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
</div><div class="card-footer text-end"><button class="btn btn-primary">Crear</button></div></form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\integrations\api_clients\create.blade.php ENDPATH**/ ?>