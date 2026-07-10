<?php $__env->startSection('title', 'Devolución POS'); ?>
<?php $__env->startSection('content'); ?>
<div class="row justify-content-center"><div class="col-lg-6">
<form method="POST" action="<?php echo e(route('pos.sales.refund.store', $order)); ?>" class="bg-white border rounded p-4">
<?php echo csrf_field(); ?>
<h1 class="h4 mb-3">Devolución venta <?php echo e($order->order_number); ?></h1>
<p class="text-muted">Total pagado: $<?php echo e(number_format($order->payments->sum('amount'), 0, ',', '.')); ?></p>
<label class="form-label">Monto a devolver</label><input name="refund_amount" type="number" min="1" max="<?php echo e((int) $order->payments->sum('amount')); ?>" step="1" class="form-control mb-3" required>
<label class="form-label">Método de devolución</label><select name="refund_method" class="form-select mb-3"><?php $__currentLoopData = $methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($method->code); ?>"><?php echo e($method->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
<label class="form-label">Motivo</label><input name="reason" class="form-control mb-3" required>
<label class="form-label">Notas</label><textarea name="notes" class="form-control mb-3"></textarea>
<label class="form-check mb-3"><input type="checkbox" name="restore_stock" value="1" class="form-check-input"> Restaurar stock</label>
<button class="btn btn-warning w-100">Registrar devolución</button>
</form>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\refunds\create.blade.php ENDPATH**/ ?>