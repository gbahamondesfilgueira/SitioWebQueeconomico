<?php $__env->startSection('title', 'Cancelar pedido'); ?>
<?php $__env->startSection('page-title', 'Cancelar '.$order->order_number); ?>
<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e(route('admin.orders.cancel.store', $order)); ?>" class="card border-0 shadow-sm"><?php echo csrf_field(); ?><div class="card-body"><label class="form-label">Motivo</label><input name="reason" class="form-control mb-3" required><label class="form-label">Notas</label><textarea name="notes" class="form-control mb-3"></textarea><label class="form-check"><input type="checkbox" name="restore_stock" value="1" class="form-check-input" checked> Restaurar stock</label></div><div class="card-footer bg-white text-end"><button class="btn btn-danger">Cancelar pedido</button></div></form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\orders\cancel.blade.php ENDPATH**/ ?>