<?php $__env->startSection('title', 'Mi cuenta'); ?>

<?php $__env->startSection('content'); ?>
    <section class="card border-0 shadow-sm mb-3">
        <div class="card-body p-4">
            <h1 class="h3 mb-1">Hola, <?php echo e($customer->first_name); ?></h1>
            <p class="text-secondary mb-3">Desde aqui puedes revisar tus compras, editar tu perfil y gestionar tus datos de envio.</p>

            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo e(route('store.account.orders.index')); ?>" class="btn btn-dark">Mis compras</a>
                <a href="<?php echo e(route('account.profile')); ?>" class="btn btn-outline-dark">Perfil</a>
                <a href="<?php echo e(route('store.pages.show', 'contacto')); ?>" class="btn btn-outline-secondary">Ayuda</a>
            </div>
        </div>
    </section>

    <?php if($customer->addresses_count < 1): ?>
        <div class="alert alert-warning border-0 shadow-sm">
            Aun no tienes direccion de envio. Agregala para que checkout use la misma informacion como direccion de facturacion.
            <a href="<?php echo e(route('account.addresses')); ?>" class="alert-link">Agregar direccion</a>
        </div>
    <?php endif; ?>

    <section class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong>Ultimas compras</strong>
            <a href="<?php echo e(route('store.account.orders.index')); ?>" class="small">Ver todas</a>
        </div>
        <div class="card-body">
            <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('store.account.orders.show', $order->order_number)); ?>" class="account-order-row">
                    <span>
                        <strong><?php echo e($order->order_number); ?></strong>
                        <small class="text-secondary d-block"><?php echo e($order->created_at->format('d/m/Y H:i')); ?> · <?php echo e($order->order_status); ?></small>
                    </span>
                    <span class="fw-bold">$<?php echo e(number_format((float) $order->grand_total, 0, ',', '.')); ?></span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-4">
                    <p class="text-secondary mb-3">Todavia no tienes compras registradas.</p>
                    <a href="<?php echo e(route('store.shop')); ?>" class="btn btn-dark">Ir a la tienda</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong>Mi lista de deseos</strong>
            <a href="<?php echo e(route('account.wishlist')); ?>" class="small">Ver lista</a>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <?php $__empty_1 = true; $__currentLoopData = $wishlistItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-6 col-md-4">
                        <a class="account-wishlist-item" href="<?php echo e($item->product ? route('store.products.show', $item->product->slug) : route('store.shop')); ?>">
                            <div class="ratio ratio-1x1 bg-light rounded overflow-hidden mb-2">
                                <?php ($image = $item->product?->images?->first()?->image_path); ?>
                                <?php if($image): ?>
                                    <img src="<?php echo e(\Illuminate\Support\Facades\Storage::url($image)); ?>" alt="<?php echo e($item->product?->name); ?>" class="object-fit-cover">
                                <?php endif; ?>
                            </div>
                            <span><?php echo e($item->product?->name ?? 'Producto no disponible'); ?></span>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12 text-center py-4">
                        <p class="text-secondary mb-3">No tienes productos marcados en tu lista de deseos.</p>
                        <a href="<?php echo e(route('store.shop')); ?>" class="btn btn-outline-dark">Explorar productos</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.account', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\account\dashboard.blade.php ENDPATH**/ ?>