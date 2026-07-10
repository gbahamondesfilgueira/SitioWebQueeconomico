<form method="POST" action="<?php echo e(route('store.checkout.billing-address')); ?>" class="card border-0 shadow-sm">
    <?php echo csrf_field(); ?>
    <div class="card-header bg-white fw-semibold">Dirección de facturación</div>
    <div class="card-body">
        <label class="form-check mb-3"><input type="checkbox" name="same_as_shipping" value="1" class="form-check-input" data-same-as-shipping> Usar la misma direccion de despacho</label>
        <?php if($companies->isNotEmpty()): ?>
            <label class="form-label">Empresa para factura</label>
            <select name="customer_company_id" class="form-select mb-3"><option value="">Persona natural</option><?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($company->id); ?>"><?php echo e($company->company_name); ?> · <?php echo e($company->rut); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
        <?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal9167914b659559f37d68cbdc0ecc5e0b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9167914b659559f37d68cbdc0ecc5e0b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.address-form','data' => ['type' => 'billing','savedAddresses' => $savedAddresses]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.address-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'billing','saved-addresses' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($savedAddresses)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9167914b659559f37d68cbdc0ecc5e0b)): ?>
<?php $attributes = $__attributesOriginal9167914b659559f37d68cbdc0ecc5e0b; ?>
<?php unset($__attributesOriginal9167914b659559f37d68cbdc0ecc5e0b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9167914b659559f37d68cbdc0ecc5e0b)): ?>
<?php $component = $__componentOriginal9167914b659559f37d68cbdc0ecc5e0b; ?>
<?php unset($__componentOriginal9167914b659559f37d68cbdc0ecc5e0b); ?>
<?php endif; ?>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between"><a href="<?php echo e(route('store.checkout.shipping-address')); ?>" class="btn btn-outline-secondary">Volver</a><button class="btn btn-dark">Continuar</button></div>
</form>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\store\checkout\steps\billing-address.blade.php ENDPATH**/ ?>