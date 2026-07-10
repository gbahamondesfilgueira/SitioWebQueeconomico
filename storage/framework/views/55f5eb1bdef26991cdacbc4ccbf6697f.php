<form method="POST" action="<?php echo e(route('store.checkout.payment-method')); ?>" class="card border-0 shadow-sm">
    <?php echo csrf_field(); ?>
    <div class="card-header bg-white fw-semibold">Método de pago</div>
    <div class="card-body">
        <?php $__currentLoopData = $paymentOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="card border mb-2"><div class="card-body"><input type="radio" name="payment_method" value="<?php echo e($key); ?>" class="form-check-input me-2" <?php if($loop->first): echo 'checked'; endif; ?>> <?php echo e($option['payment_label']); ?></div></label>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between"><a href="<?php echo e(route('store.checkout.shipping-method')); ?>" class="btn btn-outline-secondary">Volver</a><button class="btn btn-dark">Continuar</button></div>
</form>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\store\checkout\steps\payment-method.blade.php ENDPATH**/ ?>