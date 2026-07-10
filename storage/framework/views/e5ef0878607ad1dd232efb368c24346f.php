<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Qué Económico')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="<?php echo e(route('dashboard')); ?>">Qué Económico</a>
            <div class="ms-auto d-flex align-items-center gap-2">
                <?php if(auth()->guard()->check()): ?>
                    <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route('profile.edit')); ?>">Perfil</a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="btn btn-dark btn-sm" type="submit">Salir</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <?php if(isset($header)): ?>
            <div class="mb-4"><?php echo e($header); ?></div>
        <?php endif; ?>
        <?php echo e($slot); ?>

    </main>
</body>
</html>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\layouts\app.blade.php ENDPATH**/ ?>