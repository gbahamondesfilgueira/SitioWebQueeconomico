<?php $__env->startSection('title', 'Abrir caja'); ?>
<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="bg-white border rounded p-4">
            <h1 class="h4 mb-3">Abrir caja</h1>
            <?php if($openSession): ?>
                <div class="alert alert-info">Este terminal ya tiene una caja abierta.</div>
                <a class="btn btn-primary" href="<?php echo e(route('pos.cash.current')); ?>">Ver caja actual</a>
            <?php else: ?>
                <form method="POST" action="<?php echo e(route('pos.cash.open.store')); ?>" class="row g-3">
                    <?php echo csrf_field(); ?>
                    <div class="col-12">
                        <label class="form-label">Terminal POS</label>
                        <select name="terminal_id" class="form-select">
                            <?php $__currentLoopData = $terminals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($item->id); ?>" <?php if($item->id === $terminal->id): echo 'selected'; endif; ?>><?php echo e($item->name); ?> / <?php echo e($item->code); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Monto inicial</label>
                        <input name="opening_amount" type="number" min="0" step="1" value="0" class="form-control form-control-lg" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Observaciones</label>
                        <textarea name="notes" class="form-control"></textarea>
                    </div>
                    <div class="col-12 d-grid"><button class="btn btn-success btn-lg">Abrir caja</button></div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\cash\open.blade.php ENDPATH**/ ?>