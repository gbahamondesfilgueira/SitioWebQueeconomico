<div class="bg-white border rounded p-3 mb-3">
    <h2 class="h6">Pagos</h2>
    <form method="POST" action="<?php echo e(route('pos.payment.add')); ?>" class="row g-2 mb-2">
        <?php echo csrf_field(); ?>
        <div class="col-6"><select name="payment_method_id" class="form-select form-select-sm"><?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($method->id); ?>"><?php echo e($method->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <div class="col-6"><input name="amount" type="number" min="1" step="1" value="<?php echo e((int) $summary['grandTotal']); ?>" class="form-control form-control-sm"></div>
        <div class="col-8"><input name="reference" class="form-control form-control-sm" placeholder="Referencia opcional"></div>
        <div class="col-4 d-grid"><button class="btn btn-sm btn-outline-primary">Agregar</button></div>
    </form>
    <?php $__currentLoopData = $cart->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <form method="POST" action="<?php echo e(route('pos.payment.remove')); ?>" class="d-flex justify-content-between border-top py-1">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="payment_id" value="<?php echo e($payment->id); ?>">
            <span><?php echo e($payment->method->name); ?>: $<?php echo e(number_format($payment->amount, 0, ',', '.')); ?></span>
            <button class="btn btn-sm btn-link text-danger">Quitar</button>
        </form>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\components\payment-panel.blade.php ENDPATH**/ ?>