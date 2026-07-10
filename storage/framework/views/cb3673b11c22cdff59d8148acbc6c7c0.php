<?php ($setting = \App\Models\Setting::current()); ?>
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Mi cuenta'); ?> | <?php echo e($setting->store_name); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="account-page">
    <header class="account-header border-bottom bg-white">
        <div class="container py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <a href="<?php echo e(route('account.dashboard')); ?>" class="text-dark text-decoration-none fw-bold fs-5"><?php echo e($setting->store_name); ?></a>
            <nav class="d-flex flex-wrap align-items-center gap-2">
                <a href="<?php echo e(route('store.home')); ?>" class="btn btn-sm btn-outline-secondary">Home</a>
                <a href="<?php echo e(route('store.shop')); ?>" class="btn btn-sm btn-outline-secondary">Tienda</a>
                <a href="<?php echo e(route('store.account.orders.index')); ?>" class="btn btn-sm btn-outline-secondary">Mis compras</a>
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="m-0">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-sm btn-dark">Salir</button>
                </form>
            </nav>
        </div>
    </header>

    <div class="container py-4">
        <div class="row g-3">
            <aside class="col-lg-3">
                <div class="list-group shadow-sm account-side-nav">
                    <a class="list-group-item list-group-item-action <?php echo e(request()->routeIs('account.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('account.dashboard')); ?>">Resumen</a>
                    <a class="list-group-item list-group-item-action <?php echo e(request()->routeIs('account.profile') ? 'active' : ''); ?>" href="<?php echo e(route('account.profile')); ?>">Mi Perfil</a>
                    <a class="list-group-item list-group-item-action <?php echo e(request()->routeIs('account.addresses') ? 'active' : ''); ?>" href="<?php echo e(route('account.addresses')); ?>">Mis Direcciones</a>
                    <a class="list-group-item list-group-item-action <?php echo e(request()->routeIs('account.companies') ? 'active' : ''); ?>" href="<?php echo e(route('account.companies')); ?>">Mis Empresas</a>
                    <a class="list-group-item list-group-item-action <?php echo e(request()->routeIs('account.favorites') ? 'active' : ''); ?>" href="<?php echo e(route('account.favorites')); ?>">Mis Favoritos</a>
                    <a class="list-group-item list-group-item-action <?php echo e(request()->routeIs('account.wishlist') ? 'active' : ''); ?>" href="<?php echo e(route('account.wishlist')); ?>">Mi Lista de Deseos</a>
                    <a class="list-group-item list-group-item-action <?php echo e(request()->routeIs('account.documents') ? 'active' : ''); ?>" href="<?php echo e(route('account.documents')); ?>">Mis Documentos</a>
                    <a class="list-group-item list-group-item-action <?php echo e(request()->routeIs('account.rewards') ? 'active' : ''); ?>" href="<?php echo e(route('account.rewards')); ?>">Mis Puntos</a>
                    <a class="list-group-item list-group-item-action <?php echo e(request()->routeIs('account.coupons') ? 'active' : ''); ?>" href="<?php echo e(route('account.coupons')); ?>">Mis Cupones</a>
                    <a class="list-group-item list-group-item-action <?php echo e(request()->routeIs('account.security') ? 'active' : ''); ?>" href="<?php echo e(route('account.security')); ?>">Seguridad</a>
                </div>
            </aside>

            <main class="col-lg-9">
                <?php if(session('success')): ?><div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>
                <?php if(session('status')): ?><div class="alert alert-info"><?php echo e(session('status')); ?></div><?php endif; ?>
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\layouts\account.blade.php ENDPATH**/ ?>