<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <?php if (isset($component)) { $__componentOriginal898df52f4a9b89ed7169d35453e99946 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal898df52f4a9b89ed7169d35453e99946 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.breadcrumb','data' => ['items' => ['Carrito' => route('store.cart.index')]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Carrito' => route('store.cart.index')])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal898df52f4a9b89ed7169d35453e99946)): ?>
<?php $attributes = $__attributesOriginal898df52f4a9b89ed7169d35453e99946; ?>
<?php unset($__attributesOriginal898df52f4a9b89ed7169d35453e99946); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal898df52f4a9b89ed7169d35453e99946)): ?>
<?php $component = $__componentOriginal898df52f4a9b89ed7169d35453e99946; ?>
<?php unset($__componentOriginal898df52f4a9b89ed7169d35453e99946); ?>
<?php endif; ?>
        <h1 class="h3 mb-3">Carrito</h1>
        <?php if($errors->any()): ?><div class="alert alert-danger"><?php echo e($errors->first()); ?></div><?php endif; ?>
        <div class="row g-4">
            <div class="col-lg-8">
                <?php $__currentLoopData = $cart->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if (isset($component)) { $__componentOriginal6431b44d9b184d4dc398f59c7f9aa56e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6431b44d9b184d4dc398f59c7f9aa56e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.cart-item','data' => ['item' => $item]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.cart-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6431b44d9b184d4dc398f59c7f9aa56e)): ?>
<?php $attributes = $__attributesOriginal6431b44d9b184d4dc398f59c7f9aa56e; ?>
<?php unset($__attributesOriginal6431b44d9b184d4dc398f59c7f9aa56e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6431b44d9b184d4dc398f59c7f9aa56e)): ?>
<?php $component = $__componentOriginal6431b44d9b184d4dc398f59c7f9aa56e; ?>
<?php unset($__componentOriginal6431b44d9b184d4dc398f59c7f9aa56e); ?>
<?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <form method="POST" action="<?php echo e(route('store.cart.clear')); ?>"><?php echo csrf_field(); ?><button class="btn btn-outline-danger">Vaciar carrito</button></form>
            </div>
            <aside class="col-lg-4">
                <?php if (isset($component)) { $__componentOriginal4cf900763d49384ffa29fd632c3bce63 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4cf900763d49384ffa29fd632c3bce63 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.cart-summary','data' => ['summary' => $summary]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.cart-summary'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['summary' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($summary)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4cf900763d49384ffa29fd632c3bce63)): ?>
<?php $attributes = $__attributesOriginal4cf900763d49384ffa29fd632c3bce63; ?>
<?php unset($__attributesOriginal4cf900763d49384ffa29fd632c3bce63); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4cf900763d49384ffa29fd632c3bce63)): ?>
<?php $component = $__componentOriginal4cf900763d49384ffa29fd632c3bce63; ?>
<?php unset($__componentOriginal4cf900763d49384ffa29fd632c3bce63); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal28b81daedd9747d6049c36d9a238d2e0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal28b81daedd9747d6049c36d9a238d2e0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.coupon-form','data' => ['cart' => $cart]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.coupon-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['cart' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cart)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal28b81daedd9747d6049c36d9a238d2e0)): ?>
<?php $attributes = $__attributesOriginal28b81daedd9747d6049c36d9a238d2e0; ?>
<?php unset($__attributesOriginal28b81daedd9747d6049c36d9a238d2e0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal28b81daedd9747d6049c36d9a238d2e0)): ?>
<?php $component = $__componentOriginal28b81daedd9747d6049c36d9a238d2e0; ?>
<?php unset($__componentOriginal28b81daedd9747d6049c36d9a238d2e0); ?>
<?php endif; ?>
                <a href="<?php echo e(route('store.checkout.index')); ?>" class="btn btn-dark btn-lg w-100 mt-3">Iniciar checkout</a>
            </aside>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\store\cart\index.blade.php ENDPATH**/ ?>