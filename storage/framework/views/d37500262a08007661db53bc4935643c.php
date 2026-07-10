<?php use App\Support\StatusLabel; ?>

<?php $__env->startSection('title', 'Pedido '.$order->order_number); ?>
<?php $__env->startSection('page-title', 'Pedido '.$order->order_number); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $orderStatuses = ['pending','confirmed','paid','preparing','ready_to_ship','shipped','delivered','completed','cancelled','refunded'];
        $paymentStatuses = ['pending','paid','partially_paid','failed','refunded'];
    ?>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Items</div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <tbody>
                            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <?php echo e((int) $item->quantity); ?> x <?php echo e($item->product_name); ?> <?php echo e($item->variant_name); ?>

                                        <?php if($item->packComponents->isNotEmpty()): ?>
                                            <div class="small text-secondary">Componentes: <?php echo e($item->packComponents->pluck('product_name')->implode(', ')); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">$<?php echo e(number_format((float) $item->line_total, 0, ',', '.')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Historial</div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $order->histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="small border-bottom py-2">
                            <?php echo e($history->created_at?->format('d/m/Y H:i')); ?>

                            · <?php echo e(StatusLabel::generic($history->status_type)); ?>

                            · <?php echo e(StatusLabel::generic($history->old_status)); ?> -> <?php echo e(StatusLabel::generic($history->new_status)); ?>

                            · <?php echo e($history->user?->name ?? 'Sistema'); ?>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-secondary mb-0">Sin historial registrado.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h2 class="h6">Cliente</h2>
                    <p><?php echo e($order->customer_name); ?><br><?php echo e($order->customer_email); ?><br><?php echo e($order->customer_phone); ?></p>
                    <h2 class="h6">Total</h2>
                    <div class="fs-4 fw-bold">$<?php echo e(number_format((float) $order->grand_total, 0, ',', '.')); ?></div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('admin.orders.status', $order)); ?>" class="mb-2">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <label class="form-label">Estado pedido</label>
                        <select name="order_status" class="form-select mb-2">
                            <?php $__currentLoopData = $orderStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status); ?>" <?php if($order->order_status === $status): echo 'selected'; endif; ?>><?php echo e(StatusLabel::order($status)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button class="btn btn-sm btn-dark">Actualizar</button>
                    </form>

                    <form method="POST" action="<?php echo e(route('admin.orders.payment-status', $order)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <label class="form-label">Estado pago</label>
                        <select name="payment_status" class="form-select mb-2">
                            <?php $__currentLoopData = $paymentStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status); ?>" <?php if($order->payment_status === $status): echo 'selected'; endif; ?>><?php echo e(StatusLabel::payment($status)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button class="btn btn-sm btn-dark">Actualizar pago</button>
                    </form>
                </div>
            </div>

            <a href="<?php echo e(route('admin.orders.fulfillment', $order)); ?>" class="btn btn-outline-primary w-100 mb-2">Preparar pedido</a>
            <form method="POST" action="<?php echo e(route('admin.shipping.labels.generate', $order)); ?>" class="mb-2">
                <?php echo csrf_field(); ?>
                <button class="btn btn-outline-success w-100">Generar etiqueta</button>
            </form>
            <a href="<?php echo e(route('admin.orders.cancel', $order)); ?>" class="btn btn-outline-danger w-100">Cancelar pedido</a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\orders\show.blade.php ENDPATH**/ ?>