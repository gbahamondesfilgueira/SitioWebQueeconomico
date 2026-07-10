<?php $__env->startSection('title','Detalle job'); ?>
<?php $__env->startSection('content'); ?><h1 class="h4">Job #<?php echo e($job->id); ?></h1><div class="card"><div class="card-body"><pre><?php echo e(json_encode($job->toArray(), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)); ?></pre></div></div><?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\integrations\sync_jobs\show.blade.php ENDPATH**/ ?>