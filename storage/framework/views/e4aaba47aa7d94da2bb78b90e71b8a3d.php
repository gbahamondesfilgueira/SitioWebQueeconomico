<div class="table-responsive">
    <table class="table align-middle">
        <thead><tr><th>Producto</th><th style="width:120px">Cant.</th><th>Precio</th><th>Total</th><th></th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $cart->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td>
                    <strong><?php echo e($item->item_type === 'pack' ? $item->pack?->name : $item->product?->name); ?></strong>
                    <div class="text-muted small"><?php echo e($item->variant?->name); ?> <?php echo e($item->variant?->sku ?? $item->product?->sku); ?></div>
                </td>
                <td>
                    <form method="POST" action="<?php echo e(route('pos.cart.update')); ?>" class="d-flex gap-1">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="item_id" value="<?php echo e($item->id); ?>">
                        <input name="quantity" type="number" min="1" value="<?php echo e($item->quantity); ?>" class="form-control form-control-sm">
                        <button class="btn btn-sm btn-outline-secondary">OK</button>
                    </form>
                </td>
                <td>$<?php echo e(number_format($item->final_unit_price, 0, ',', '.')); ?></td>
                <td>$<?php echo e(number_format($item->line_total, 0, ',', '.')); ?></td>
                <td>
                    <form method="POST" action="<?php echo e(route('pos.cart.remove')); ?>"><?php echo csrf_field(); ?><input type="hidden" name="item_id" value="<?php echo e($item->id); ?>"><button class="btn btn-sm btn-outline-danger">Quitar</button></form>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="5" class="text-muted">Agrega productos para iniciar la venta.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\components\cart-items.blade.php ENDPATH**/ ?>