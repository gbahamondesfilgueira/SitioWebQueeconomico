<?php $__env->startSection('title', 'Cerrar caja'); ?>
<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-lg-7">
        <div class="bg-white border rounded p-3">
            <h1 class="h4">Resumen para cierre</h1>
            <?php echo $__env->make('pos.cash.partials.totals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
    <div class="col-lg-5">
        <form method="POST" action="<?php echo e(route('pos.cash.close.store')); ?>" class="bg-white border rounded p-3">
            <?php echo csrf_field(); ?>
            <h2 class="h5">Conteo final</h2>
            <label class="form-label">Efectivo contado</label>
            <input name="counted_cash_amount" type="number" min="0" step="1" value="<?php echo e((int) $summary['cashExpected']); ?>" class="form-control form-control-lg mb-3" required>
            <label class="form-label">Observaciones</label>
            <textarea name="notes" class="form-control mb-3"></textarea>
            <button class="btn btn-danger btn-lg w-100">Cerrar caja</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\cash\close.blade.php ENDPATH**/ ?>