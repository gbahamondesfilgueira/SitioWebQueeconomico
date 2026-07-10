<form method="POST" action="<?php echo e(route('store.checkout.shipping-method')); ?>" class="card border-0 shadow-sm">
    <?php echo csrf_field(); ?>
    <div class="card-header bg-white fw-semibold">Método de envío</div>
    <div class="card-body">
        <?php if(($shippingQuotes ?? collect())->isNotEmpty()): ?>
            <div class="mb-3">
                <div class="fw-semibold mb-2">Cotizaciones disponibles</div>
                <?php $__currentLoopData = $shippingQuotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="card border mb-2"><div class="card-body d-flex justify-content-between align-items-center"><span><input type="radio" name="shipping_method" value="quote:<?php echo e($quote->id); ?>" class="form-check-input me-2" <?php if($loop->first): echo 'checked'; endif; ?>> <?php echo e($quote->carrier?->name); ?> · <?php echo e($quote->service?->name); ?> <span class="text-secondary small"><?php echo e($quote->estimated_days_min); ?>-<?php echo e($quote->estimated_days_max); ?> día(s)</span></span><strong>$<?php echo e(number_format((float) $quote->price, 0, ',', '.')); ?></strong></div></label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
        <div class="fw-semibold mb-2">Opciones manuales</div>
        <?php $__currentLoopData = $shippingOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="card border mb-2"><div class="card-body d-flex justify-content-between align-items-center"><span><input type="radio" name="shipping_method" value="<?php echo e($key); ?>" class="form-check-input me-2" <?php if(($shippingQuotes ?? collect())->isEmpty() && $loop->first): echo 'checked'; endif; ?>> <?php echo e($option['service_name']); ?> <span class="text-secondary small"><?php echo e($option['estimated_days']); ?> día(s)</span></span><strong>$<?php echo e(number_format($option['estimated_price'], 0, ',', '.')); ?></strong></div></label>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between"><a href="<?php echo e(route('store.checkout.billing-address')); ?>" class="btn btn-outline-secondary">Volver</a><button class="btn btn-dark">Continuar</button></div>
</form>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\store\checkout\steps\shipping-method.blade.php ENDPATH**/ ?>