<?php $__env->startSection('title', 'Crear terminal POS'); ?>
<?php $__env->startSection('content'); ?>
<h1 class="h4 mb-3">Crear terminal POS</h1>
<?php echo $__env->make('admin.pos.terminals.form', ['action' => route('admin.pos.terminals.store'), 'method' => 'POST', 'terminal' => null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\pos\terminals\create.blade.php ENDPATH**/ ?>