<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <?php if (isset($component)) { $__componentOriginal898df52f4a9b89ed7169d35453e99946 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal898df52f4a9b89ed7169d35453e99946 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.breadcrumb','data' => ['items' => ['Checkout' => route('store.checkout.index')]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Checkout' => route('store.checkout.index')])]); ?>
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
        <h1 class="h3 mb-3">Checkout</h1>
        <?php if($errors->any()): ?><div class="alert alert-danger"><?php echo e($errors->first()); ?></div><?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal33f706c38a928bbdc37e76b2265609d9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33f706c38a928bbdc37e76b2265609d9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.checkout-steps','data' => ['step' => $step]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.checkout-steps'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['step' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($step)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal33f706c38a928bbdc37e76b2265609d9)): ?>
<?php $attributes = $__attributesOriginal33f706c38a928bbdc37e76b2265609d9; ?>
<?php unset($__attributesOriginal33f706c38a928bbdc37e76b2265609d9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal33f706c38a928bbdc37e76b2265609d9)): ?>
<?php $component = $__componentOriginal33f706c38a928bbdc37e76b2265609d9; ?>
<?php unset($__componentOriginal33f706c38a928bbdc37e76b2265609d9); ?>
<?php endif; ?>
        <div class="row g-4">
            <section class="col-lg-8">
                <?php echo $__env->make("store.checkout.steps.$step", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </section>
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
            </aside>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\store\checkout\index.blade.php ENDPATH**/ ?>