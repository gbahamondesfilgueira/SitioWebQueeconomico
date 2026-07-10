<?php use App\Support\StatusLabel; ?>

<?php $__env->startSection('title', 'Pedidos'); ?>
<?php $__env->startSection('page-title', 'Pedidos'); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $orderStatuses = ['pending','confirmed','paid','preparing','ready_to_ship','shipped','delivered','completed','cancelled','refunded'];
        $paymentStatuses = ['pending','paid','partially_paid','failed','refunded'];
    ?>

    <form class="card border-0 shadow-sm mb-3">
        <div class="card-body row g-2">
            <div class="col-md-2">
                <input name="order_number" class="form-control" placeholder="Pedido" value="<?php echo e(request('order_number')); ?>">
            </div>
            <div class="col-md-2">
                <input name="customer" class="form-control" placeholder="Cliente" value="<?php echo e(request('customer')); ?>">
            </div>
            <div class="col-md-2">
                <select name="order_status" class="form-select">
                    <option value="">Estado pedido</option>
                    <?php $__currentLoopData = $orderStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($status); ?>" <?php if(request('order_status') === $status): echo 'selected'; endif; ?>><?php echo e(StatusLabel::order($status)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="payment_status" class="form-select">
                    <option value="">Estado pago</option>
                    <?php $__currentLoopData = $paymentStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($status); ?>" <?php if(request('payment_status') === $status): echo 'selected'; endif; ?>><?php echo e(StatusLabel::payment($status)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-dark w-100">Filtrar</button>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Numero</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Pedido</th>
                        <th>Pago</th>
                        <th>Preparacion</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($order->order_number); ?></td>
                            <td>
                                <?php echo e($order->customer_name); ?>

                                <div class="small text-secondary"><?php echo e($order->customer_email); ?></div>
                            </td>
                            <td>$<?php echo e(number_format((float) $order->grand_total, 0, ',', '.')); ?></td>
                            <td><span class="badge text-bg-light"><?php echo e(StatusLabel::order($order->order_status)); ?></span></td>
                            <td><span class="badge text-bg-light"><?php echo e(StatusLabel::payment($order->payment_status)); ?></span></td>
                            <td><span class="badge text-bg-light"><?php echo e(StatusLabel::fulfillment($order->fulfillment_status)); ?></span></td>
                            <td><?php echo e($order->created_at->format('d/m/Y H:i')); ?></td>
                            <td><a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="btn btn-sm btn-outline-dark">Ver</a></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="8" class="text-center text-secondary py-4">Sin pedidos.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white"><?php echo e($orders->links()); ?></div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\orders\index.blade.php ENDPATH**/ ?>