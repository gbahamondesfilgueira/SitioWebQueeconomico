<?php $__env->startSection('title','Detalle log'); ?>
<?php $__env->startSection('content'); ?>
<h1 class="h4">Log #<?php echo e($log->id); ?></h1>
<div class="card"><div class="card-body"><p><strong>Evento:</strong> <?php echo e($log->event_type); ?></p><p><strong>Estado:</strong> <?php echo e($log->status); ?></p><p><strong>Error:</strong> <?php echo e($log->error_message); ?></p><h2 class="h6">Request</h2><pre><?php echo e(json_encode($log->request_payload, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)); ?></pre><h2 class="h6">Response</h2><pre><?php echo e(json_encode($log->response_payload, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)); ?></pre></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\integrations\logs\show.blade.php ENDPATH**/ ?>