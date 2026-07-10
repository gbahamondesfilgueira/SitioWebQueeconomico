<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comprobante <?php echo e($order->order_number); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>@media print {.no-print{display:none}.receipt{max-width:320px}}</style>
</head>
<body class="bg-light">
<main class="receipt mx-auto bg-white p-3 my-3 border">
    <div class="text-center">
        <h1 class="h5 mb-0"><?php echo e($settings->store_name); ?></h1>
        <div class="small"><?php echo e($settings->rut); ?> · <?php echo e($settings->address); ?></div>
    </div>
    <hr>
    <div class="small">
        <div><strong>Venta:</strong> <?php echo e($order->order_number); ?></div>
        <div><strong>Fecha:</strong> <?php echo e($order->created_at->format('d/m/Y H:i')); ?></div>
        <div><strong>Vendedor:</strong> <?php echo e($order->seller?->name); ?></div>
        <div><strong>Terminal:</strong> <?php echo e($order->posTerminal?->name); ?></div>
        <div><strong>Cliente:</strong> <?php echo e($order->customer_name); ?></div>
    </div>
    <hr>
    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="d-flex justify-content-between small">
            <span><?php echo e($item->quantity); ?> x <?php echo e($item->product_name); ?> <?php echo e($item->variant_name); ?></span>
            <strong>$<?php echo e(number_format($item->line_total, 0, ',', '.')); ?></strong>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <hr>
    <div class="d-flex justify-content-between"><span>Descuentos</span><span>$<?php echo e(number_format($order->item_discount_total + $order->coupon_discount_total, 0, ',', '.')); ?></span></div>
    <div class="d-flex justify-content-between"><span>IVA</span><span>$<?php echo e(number_format($order->tax_total, 0, ',', '.')); ?></span></div>
    <div class="d-flex justify-content-between h5"><span>Total</span><strong>$<?php echo e(number_format($order->grand_total, 0, ',', '.')); ?></strong></div>
    <hr>
    <div class="small">
        <?php $__currentLoopData = $order->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div><?php echo e($payment->payment_label); ?>: $<?php echo e(number_format($payment->amount, 0, ',', '.')); ?></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <p class="text-center mt-3"><?php echo e($settings->pos_default_receipt_message ?? 'Gracias por su compra'); ?></p>
    <div class="d-grid gap-2 no-print">
        <button class="btn btn-primary" onclick="window.print()">Imprimir</button>
        <a class="btn btn-outline-secondary" href="<?php echo e(route('pos.sale.create')); ?>">Nueva venta</a>
    </div>
</main>
</body>
</html>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\receipt.blade.php ENDPATH**/ ?>