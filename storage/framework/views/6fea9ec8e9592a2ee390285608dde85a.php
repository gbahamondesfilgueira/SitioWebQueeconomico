<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <?php if($newOrders > 0): ?>
        <div class="alert alert-warning border-0 shadow-sm d-flex justify-content-between align-items-center">
            <div><strong><?php echo e($newOrders); ?> pedido(s) nuevo(s)</strong> en las ultimas 24 horas.</div>
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-sm btn-dark">Ver pedidos</a>
        </div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-secondary small">Ventas hoy</div><div class="fs-4 fw-bold">$<?php echo e(number_format((float) $todaySales, 0, ',', '.')); ?></div></div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-secondary small">Ventas mes</div><div class="fs-4 fw-bold">$<?php echo e(number_format((float) $monthSales, 0, ',', '.')); ?></div></div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-secondary small">Pedidos pendientes</div><div class="fs-4 fw-bold text-warning"><?php echo e($pendingOrders); ?></div></div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-secondary small">Stock bajo minimo</div><div class="fs-4 fw-bold text-danger"><?php echo e($lowStockProducts); ?></div></div></div></div>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong>Ultimos pedidos</strong>
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="small">Ver todos</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead><tr><th>Pedido</th><th>Cliente</th><th>Total</th><th>Estado</th><th>Fecha</th></tr></thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $latestOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><a href="<?php echo e(route('admin.orders.show', $order)); ?>"><?php echo e($order->order_number); ?></a></td>
                                    <td><?php echo e($order->customer_name); ?></td>
                                    <td>$<?php echo e(number_format((float) $order->grand_total, 0, ',', '.')); ?></td>
                                    <td><span class="badge text-bg-light"><?php echo e($order->order_status); ?></span></td>
                                    <td class="small text-secondary"><?php echo e($order->created_at?->format('d/m/Y H:i')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="5" class="text-center text-secondary py-4">Sin pedidos todavia.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Estado rapido</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6"><div class="text-secondary small">Clientes activos</div><div class="fs-5 fw-bold"><?php echo e($activeCustomers); ?></div></div>
                        <div class="col-6"><div class="text-secondary small">Productos con stock</div><div class="fs-5 fw-bold"><?php echo e($stockedProducts); ?></div></div>
                        <div class="col-6"><div class="text-secondary small">Bodegas activas</div><div class="fs-5 fw-bold"><?php echo e($activeWarehouses); ?></div></div>
                        <div class="col-6"><div class="text-secondary small">Ajustes pendientes</div><div class="fs-5 fw-bold"><?php echo e($pendingAdjustments); ?></div></div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Ultimos eventos</div>
                <div class="list-group list-group-flush">
                    <?php $__empty_1 = true; $__currentLoopData = $auditLogs->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="list-group-item">
                            <div class="small text-secondary"><?php echo e($log->created_at?->format('d/m/Y H:i')); ?> · <?php echo e($log->module); ?></div>
                            <div><?php echo e($log->description); ?></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="list-group-item text-secondary">Sin eventos registrados.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\dashboard.blade.php ENDPATH**/ ?>