<?php $__env->startSection('title', 'Ingreso de caja'); ?>
<?php $__env->startSection('content'); ?>
<div class="row justify-content-center"><div class="col-lg-6"><form method="POST" action="<?php echo e(route('pos.cash.income.store')); ?>" class="bg-white border rounded p-4">
<?php echo csrf_field(); ?>
<h1 class="h4 mb-3">Registrar ingreso</h1>
<label class="form-label">Monto</label><input name="amount" type="number" min="1" step="1" class="form-control mb-3" required>
<label class="form-label">Medio de pago</label><select name="payment_method_id" class="form-select mb-3"><option value="">Sin medio</option><?php $__currentLoopData = $methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($method->id); ?>"><?php echo e($method->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
<label class="form-label">Descripción</label><input name="description" class="form-control mb-3" required>
<button class="btn btn-success w-100">Guardar ingreso</button>
</form></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\cash\income.blade.php ENDPATH**/ ?>