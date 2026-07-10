<?php $__env->startSection('title', 'Error interno'); ?>
<?php $__env->startSection('content'); ?>
    <section class="container py-5 text-center">
        <h1 class="display-5 fw-bold">500</h1>
        <p class="lead">Ocurrió un error interno. Nuestro equipo puede revisar los registros del sistema.</p>
        <a href="<?php echo e(route('store.home')); ?>" class="btn btn-dark">Volver al inicio</a>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\errors\500.blade.php ENDPATH**/ ?>