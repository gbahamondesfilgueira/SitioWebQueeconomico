<?php $__env->startSection('title', 'Mis compras'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Mis compras</h1>
        <a href="<?php echo e(route('store.shop')); ?>" class="btn btn-outline-dark btn-sm">Ir a la tienda</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Numero</th>
                        <th>Total</th>
                        <th>Pedido</th>
                        <th>Pago</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($order->order_number); ?></td>
                            <td>$<?php echo e(number_format((float) $order->grand_total, 0, ',', '.')); ?></td>
                            <td><?php echo e($order->order_status); ?></td>
                            <td><?php echo e($order->payment_status); ?></td>
                            <td><?php echo e($order->created_at->format('d/m/Y H:i')); ?></td>
                            <td><a href="<?php echo e(route('store.account.orders.show', $order->order_number)); ?>" class="btn btn-sm btn-outline-dark">Ver</a></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <p class="text-secondary mb-3">No tienes compras registradas.</p>
                                <a href="<?php echo e(route('store.shop')); ?>" class="btn btn-dark">Comprar en la tienda</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3"><?php echo e($orders->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.account', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\store\account\orders\index.blade.php ENDPATH**/ ?>