<?php $__env->startSection('content'); ?>
    <div class="container py-5">
        <?php
            $payment = $order->payments->first();
            $whatsappText = 'Hola, Este es el numero '.$order->order_number.' de pedido, cuales son los datos bancarios bancarios para transferir';
            $whatsappUrl = 'https://wa.me/56950031384?text='.rawurlencode($whatsappText);
        ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3">Pedido confirmado</h1>
                <p class="text-secondary">Tu pedido <?php echo e($order->order_number); ?> fue creado correctamente.</p>
                <div class="row g-3">
                    <div class="col-md-4"><strong>Total</strong><div class="fs-4">$<?php echo e(number_format((float) $order->grand_total, 0, ',', '.')); ?></div></div>
                    <div class="col-md-4"><strong>Estado</strong><div><?php echo e($order->order_status); ?></div></div>
                    <div class="col-md-4"><strong>Pago</strong><div><?php echo e($payment?->payment_label); ?></div></div>
                </div>
                <?php if($payment?->payment_method === 'bank_transfer'): ?>
                    <div class="alert alert-warning mt-4">
                        Para completar tu compra por transferencia, escríbenos por WhatsApp indicando tu número de pedido.
                        <div class="mt-3">
                            <a href="<?php echo e($whatsappUrl); ?>" target="_blank" rel="noopener" class="btn btn-success">Solicitar datos bancarios por WhatsApp</a>
                        </div>
                    </div>
                <?php endif; ?>
                <hr>
                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="d-flex justify-content-between py-2 border-bottom"><span><?php echo e($item->quantity); ?> x <?php echo e($item->product_name); ?> <?php echo e($item->variant_name); ?></span><strong>$<?php echo e(number_format((float) $item->line_total, 0, ',', '.')); ?></strong></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('store.home')); ?>" class="btn btn-dark mt-3">Volver a la tienda</a>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\store\orders\confirmed.blade.php ENDPATH**/ ?>