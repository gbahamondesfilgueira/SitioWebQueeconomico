<?php $__env->startSection('title', 'Detalle caja POS'); ?>
<?php $__env->startSection('content'); ?>
<h1 class="h4 mb-3">Caja #<?php echo e($session->id); ?> · <?php echo e($session->terminal?->name); ?></h1>
<div class="row g-3 mb-3">
<div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Estado</div><div class="h4"><?php echo e($session->status); ?></div></div></div></div>
<div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Inicial</div><div class="h4">$<?php echo e(number_format($session->opening_amount, 0, ',', '.')); ?></div></div></div></div>
<div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Esperado</div><div class="h4">$<?php echo e(number_format($summary['cashExpected'], 0, ',', '.')); ?></div></div></div></div>
<div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Diferencia</div><div class="h4">$<?php echo e(number_format($session->cash_difference ?? 0, 0, ',', '.')); ?></div></div></div></div>
</div>
<div class="card"><div class="card-body table-responsive">
<table class="table"><thead><tr><th>Fecha</th><th>Tipo</th><th>Monto</th><th>Medio</th><th>Descripción</th></tr></thead><tbody>
<?php $__currentLoopData = $session->movements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr><td><?php echo e($movement->created_at->format('d/m/Y H:i')); ?></td><td><?php echo e($movement->movement_type); ?></td><td>$<?php echo e(number_format($movement->amount, 0, ',', '.')); ?></td><td><?php echo e($movement->paymentMethod?->name); ?></td><td><?php echo e($movement->description); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody></table>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\cash-registers\show.blade.php ENDPATH**/ ?>