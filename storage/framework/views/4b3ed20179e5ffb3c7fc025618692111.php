<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'POS'); ?> - Qué Económico</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="<?php echo e(route('pos.dashboard')); ?>">POS Qué Económico</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#posNav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="posNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?php echo e(route('pos.sale.create')); ?>">Venta</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo e(route('pos.cash.current')); ?>">Caja</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo e(route('pos.quotes.index')); ?>">Cotizaciones</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo e(route('pos.reservations.index')); ?>">Reservas</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo e(route('admin.dashboard')); ?>">Admin</a></li>
            </ul>
            <span class="navbar-text"><?php echo e(auth()->user()->name); ?></span>
        </div>
    </div>
</nav>
<main class="container-fluid py-3">
    <?php if(session('success')): ?> <div class="alert alert-success"><?php echo e(session('success')); ?></div> <?php endif; ?>
    <?php if($errors->any()): ?> <div class="alert alert-danger"><?php echo e($errors->first()); ?></div> <?php endif; ?>
    <?php echo $__env->yieldContent('content'); ?>
</main>
</body>
</html>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\layouts\pos.blade.php ENDPATH**/ ?>