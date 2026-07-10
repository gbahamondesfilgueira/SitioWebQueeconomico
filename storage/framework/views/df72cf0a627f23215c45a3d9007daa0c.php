<?php $__env->startSection('title', 'Página no encontrada'); ?>
<?php $__env->startSection('content'); ?>
    <section class="container py-5 text-center">
        <h1 class="display-5 fw-bold">404</h1>
        <p class="lead">La página que buscas no existe o fue movida.</p>
        <a href="<?php echo e(route('store.home')); ?>" class="btn btn-dark">Ir a la tienda</a>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\errors\404.blade.php ENDPATH**/ ?>