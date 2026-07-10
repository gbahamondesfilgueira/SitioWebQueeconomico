<?php $__env->startSection('title','Etiquetas'); ?>
<?php $__env->startSection('page-title','Etiquetas'); ?>
<?php $__env->startSection('content'); ?>
    <div class="card border-0 shadow-sm"><table class="table mb-0"><thead><tr><th>Etiqueta</th><th>Pedido</th><th>Tracking</th><th>Estado</th><th></th></tr></thead><tbody><?php $__currentLoopData = $labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr><td><?php echo e($label->label_number); ?></td><td><?php echo e($label->order?->order_number); ?></td><td><?php echo e($label->tracking_number); ?></td><td><?php echo e($label->status); ?></td><td><a href="<?php echo e(route('admin.shipping.labels.show',$label)); ?>" class="btn btn-sm btn-outline-dark">Ver</a></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></tbody></table></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\shipping\labels\index.blade.php ENDPATH**/ ?>