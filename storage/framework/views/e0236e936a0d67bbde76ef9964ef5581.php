<?php $__env->startSection('title', 'Acceso no autorizado'); ?>
<?php $__env->startSection('content'); ?>
    <section class="container py-5 text-center">
        <h1 class="display-5 fw-bold">403</h1>
        <p class="lead">No tienes permisos para acceder a esta sección.</p>
        <a href="<?php echo e(url('/')); ?>" class="btn btn-dark">Volver al inicio</a>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\errors\403.blade.php ENDPATH**/ ?>