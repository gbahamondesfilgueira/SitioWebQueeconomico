<?php $__env->startSection('content'); ?>
    <div class="container py-5">
        <div class="card border-0 shadow-sm text-center"><div class="card-body p-5"><h1 class="h3">Tu carrito está vacío</h1><p class="text-secondary">Agrega productos o packs para iniciar checkout.</p><a href="<?php echo e(route('store.shop')); ?>" class="btn btn-dark">Ir a la tienda</a></div></div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\store\cart\empty.blade.php ENDPATH**/ ?>