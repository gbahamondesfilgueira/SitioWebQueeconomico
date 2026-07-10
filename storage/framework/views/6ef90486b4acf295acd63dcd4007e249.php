<?php $__env->startSection('title', 'Reserva POS'); ?>
<?php $__env->startSection('content'); ?>
<div class="bg-white border rounded p-3">
<div class="d-flex justify-content-between"><div><h1 class="h4"><?php echo e($reservation->reservation_number); ?></h1><p class="text-muted"><?php echo e($reservation->customer?->display_name ?? 'Cliente mostrador'); ?> · <?php echo e($reservation->status); ?></p></div><form method="POST" action="<?php echo e(route('pos.reservations.convert', $reservation)); ?>"><?php echo csrf_field(); ?><button class="btn btn-success">Convertir a venta</button></form></div>
<table class="table"><thead><tr><th>Producto</th><th>Cantidad</th></tr></thead><tbody>
<?php $__currentLoopData = $reservation->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr><td><?php echo e($item->product?->name); ?> <?php echo e($item->variant?->name); ?></td><td><?php echo e($item->quantity); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody></table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\reservations\show.blade.php ENDPATH**/ ?>