<?php $__env->startSection('title', 'Dashboard POS'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-0">Dashboard POS</h1>
        <div class="text-muted">Terminal: <?php echo e($terminal->name); ?> / <?php echo e($terminal->code); ?></div>
    </div>
    <a href="<?php echo e(route('pos.sale.create')); ?>" class="btn btn-primary btn-lg">Nueva venta</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Ventas del día</div><div class="h2"><?php echo e($salesToday); ?></div></div></div>
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Total vendido</div><div class="h2">$<?php echo e(number_format($totalToday, 0, ',', '.')); ?></div></div></div>
    <div class="col-md-2"><div class="bg-white border rounded p-3"><div class="text-muted">Cotizaciones</div><div class="h2"><?php echo e($openQuotes); ?></div></div></div>
    <div class="col-md-2"><div class="bg-white border rounded p-3"><div class="text-muted">Reservas</div><div class="h2"><?php echo e($activeReservations); ?></div></div></div>
    <div class="col-md-2"><div class="bg-white border rounded p-3"><div class="text-muted">Bajo stock</div><div class="h2"><?php echo e($lowStock); ?></div></div></div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Caja</div><div class="h3"><?php echo e($cashSession ? 'Abierta' : 'Cerrada'); ?></div><a href="<?php echo e(route($cashSession ? 'pos.cash.current' : 'pos.cash.open')); ?>" class="btn btn-sm btn-outline-primary"><?php echo e($cashSession ? 'Ver caja' : 'Abrir caja'); ?></a></div></div>
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Monto inicial</div><div class="h3">$<?php echo e(number_format($cashSummary['opening'] ?? 0, 0, ',', '.')); ?></div></div></div>
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Efectivo esperado</div><div class="h3">$<?php echo e(number_format($cashSummary['cashExpected'] ?? 0, 0, ',', '.')); ?></div></div></div>
    <div class="col-md-3"><div class="bg-white border rounded p-3"><div class="text-muted">Diferencia</div><div class="h3">$<?php echo e(number_format($cashSession?->cash_difference ?? 0, 0, ',', '.')); ?></div></div></div>
</div>

<div class="bg-white border rounded p-3">
    <h2 class="h5">Últimas ventas</h2>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Número</th><th>Cliente</th><th>Total</th><th>Fecha</th><th></th></tr></thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $latestSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($order->order_number); ?></td>
                    <td><?php echo e($order->customer_name); ?></td>
                    <td>$<?php echo e(number_format($order->grand_total, 0, ',', '.')); ?></td>
                    <td><?php echo e($order->created_at->format('d/m/Y H:i')); ?></td>
                    <td><a class="btn btn-sm btn-outline-secondary" href="<?php echo e(route('pos.orders.receipt', $order)); ?>">Comprobante</a></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="text-muted">Sin ventas POS todavía.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\dashboard.blade.php ENDPATH**/ ?>