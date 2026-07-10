<?php $__env->startSection('title','Etiqueta'); ?>
<?php $__env->startSection('page-title','Etiqueta '.$label->label_number); ?>
<?php $__env->startSection('content'); ?>
    <div class="card border-0 shadow-sm"><div class="card-body"><p>Pedido: <?php echo e($label->order?->order_number); ?></p><p>Tracking: <?php echo e($label->tracking_number); ?></p><a href="<?php echo e(route('admin.shipping.labels.print',$label)); ?>" class="btn btn-dark">Imprimir</a></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\shipping\labels\show.blade.php ENDPATH**/ ?>