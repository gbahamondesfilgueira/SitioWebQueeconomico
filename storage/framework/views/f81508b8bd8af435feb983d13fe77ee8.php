<div class="bg-white border rounded p-3">
    <h2 class="h6">Resumen</h2>
    <div class="d-flex justify-content-between"><span>Subtotal</span><strong>$<?php echo e(number_format($summary['subtotal'], 0, ',', '.')); ?></strong></div>
    <div class="d-flex justify-content-between"><span>Descuentos ítems</span><span>$<?php echo e(number_format($summary['itemDiscounts'], 0, ',', '.')); ?></span></div>
    <div class="d-flex justify-content-between"><span>Cupones</span><span>$<?php echo e(number_format($summary['couponDiscount'], 0, ',', '.')); ?></span></div>
    <div class="d-flex justify-content-between"><span>Manual</span><span>$<?php echo e(number_format($summary['manualDiscount'], 0, ',', '.')); ?></span></div>
    <div class="d-flex justify-content-between"><span>IVA</span><span>$<?php echo e(number_format($summary['taxTotal'], 0, ',', '.')); ?></span></div>
    <hr>
    <div class="d-flex justify-content-between align-items-center"><span class="h5">Total</span><strong class="h3">$<?php echo e(number_format($summary['grandTotal'], 0, ',', '.')); ?></strong></div>
    <div class="d-flex justify-content-between text-muted"><span>Pagado</span><span>$<?php echo e(number_format($summary['paid'], 0, ',', '.')); ?></span></div>
    <form method="POST" action="<?php echo e(route('pos.coupon.apply')); ?>" class="input-group my-2"><?php echo csrf_field(); ?><input name="coupon_code" class="form-control" placeholder="Cupón"><button class="btn btn-outline-secondary">Aplicar</button></form>
    <form method="POST" action="<?php echo e(route('pos.discount.request')); ?>" class="row g-2 my-2">
        <?php echo csrf_field(); ?>
        <div class="col-4"><select name="discount_type" class="form-select"><option value="percentage">%</option><option value="fixed">$</option></select></div>
        <div class="col-4"><input name="discount_value" type="number" min="0.01" step="0.01" class="form-control" placeholder="Valor"></div>
        <div class="col-4"><input name="reason" class="form-control" placeholder="Motivo"></div>
        <div class="col-12 d-grid"><button class="btn btn-outline-warning">Descuento manual</button></div>
    </form>
    <div class="d-grid gap-2">
        <form method="POST" action="<?php echo e(route('pos.confirm-sale')); ?>"><?php echo csrf_field(); ?><button class="btn btn-success btn-lg w-100">Confirmar venta</button></form>
        <form method="POST" action="<?php echo e(route('pos.quotes.create-from-cart')); ?>"><?php echo csrf_field(); ?><button class="btn btn-outline-secondary w-100">Crear cotización</button></form>
        <form method="POST" action="<?php echo e(route('pos.reservations.create-from-cart')); ?>"><?php echo csrf_field(); ?><button class="btn btn-outline-secondary w-100">Crear reserva</button></form>
    </div>
</div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\components\summary.blade.php ENDPATH**/ ?>