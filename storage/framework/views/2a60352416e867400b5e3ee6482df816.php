<form method="POST" action="<?php echo e(route('store.checkout.shipping-address')); ?>" class="card border-0 shadow-sm" data-checkout-shipping-form>
    <?php echo csrf_field(); ?>
    <div class="card-header bg-white fw-semibold">Dirección de despacho</div>
    <div class="card-body"><?php if (isset($component)) { $__componentOriginal9167914b659559f37d68cbdc0ecc5e0b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9167914b659559f37d68cbdc0ecc5e0b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.address-form','data' => ['type' => 'shipping','savedAddresses' => $savedAddresses]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.address-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'shipping','saved-addresses' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($savedAddresses)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9167914b659559f37d68cbdc0ecc5e0b)): ?>
<?php $attributes = $__attributesOriginal9167914b659559f37d68cbdc0ecc5e0b; ?>
<?php unset($__attributesOriginal9167914b659559f37d68cbdc0ecc5e0b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9167914b659559f37d68cbdc0ecc5e0b)): ?>
<?php $component = $__componentOriginal9167914b659559f37d68cbdc0ecc5e0b; ?>
<?php unset($__componentOriginal9167914b659559f37d68cbdc0ecc5e0b); ?>
<?php endif; ?></div>
    <div class="card-footer bg-white d-flex justify-content-between"><a href="<?php echo e(route('store.checkout.index')); ?>" class="btn btn-outline-secondary">Volver</a><button class="btn btn-dark">Continuar</button></div>
</form>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\store\checkout\steps\shipping-address.blade.php ENDPATH**/ ?>