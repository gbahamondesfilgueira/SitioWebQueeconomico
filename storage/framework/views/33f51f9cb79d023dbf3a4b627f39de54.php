<?php $__env->startSection('title', 'Cotización POS'); ?>
<?php $__env->startSection('content'); ?>
<div class="bg-white border rounded p-3">
<div class="d-flex justify-content-between"><div><h1 class="h4"><?php echo e($quote->quote_number); ?></h1><p class="text-muted"><?php echo e($quote->customer?->display_name ?? 'Cliente mostrador'); ?></p></div><form method="POST" action="<?php echo e(route('pos.quotes.convert', $quote)); ?>"><?php echo csrf_field(); ?><button class="btn btn-success">Convertir a venta</button></form></div>
<table class="table"><thead><tr><th>Ítem</th><th>Cantidad</th><th>Total</th></tr></thead><tbody>
<?php $__currentLoopData = $quote->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr><td><?php echo e($item->item_type === 'pack' ? $item->pack?->name : $item->product?->name); ?> <?php echo e($item->variant?->name); ?></td><td><?php echo e($item->quantity); ?></td><td>$<?php echo e(number_format($item->line_total, 0, ',', '.')); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody></table>
<div class="text-end h4">Total: $<?php echo e(number_format($quote->grand_total, 0, ',', '.')); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\quotes\show.blade.php ENDPATH**/ ?>