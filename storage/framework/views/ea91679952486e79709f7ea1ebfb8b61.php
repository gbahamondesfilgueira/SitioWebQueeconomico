<?php $__env->startSection('title', 'Sesión expirada'); ?>
<?php $__env->startSection('content'); ?>
    <section class="container py-5 text-center">
        <h1 class="display-5 fw-bold">419</h1>
        <p class="lead">Tu sesión expiró. Recarga la página e intenta nuevamente.</p>
        <a href="<?php echo e(url()->previous()); ?>" class="btn btn-dark">Volver</a>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\errors\419.blade.php ENDPATH**/ ?>