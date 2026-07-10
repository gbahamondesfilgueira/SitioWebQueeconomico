<?php $__env->startSection('title','Detalle integración'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between mb-3"><h1 class="h4"><?php echo e($integration->name); ?></h1><a class="btn btn-outline-primary" href="<?php echo e(route('admin.integrations.edit',$integration)); ?>">Editar</a></div>
<div class="row g-3 mb-3">
<div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Estado</div><div class="h4"><?php echo e($integration->status); ?></div></div></div></div>
<div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Mapeos</div><div class="h4"><?php echo e($integration->product_mappings_count); ?></div></div></div></div>
<div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Jobs</div><div class="h4"><?php echo e($integration->sync_jobs_count); ?></div></div></div></div>
<div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Webhooks</div><div class="h4"><?php echo e($integration->webhook_events_count); ?></div></div></div></div>
</div>
<div class="card mb-3"><div class="card-body"><h2 class="h5">Credenciales enmascaradas</h2><pre><?php echo e(json_encode($masked, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)); ?></pre></div></div>
<form class="d-inline" method="POST" action="<?php echo e(route('admin.integrations.test',$integration)); ?>"><?php echo csrf_field(); ?><button class="btn btn-secondary">Probar conexión</button></form>
<form class="d-inline" method="POST" action="<?php echo e(route('admin.integrations.sync-stock',$integration)); ?>"><?php echo csrf_field(); ?><button class="btn btn-outline-secondary">Sync stock</button></form>
<form class="d-inline" method="POST" action="<?php echo e(route('admin.integrations.sync-prices',$integration)); ?>"><?php echo csrf_field(); ?><button class="btn btn-outline-secondary">Sync precios</button></form>
<form class="d-inline" method="POST" action="<?php echo e(route('admin.integrations.import-orders',$integration)); ?>"><?php echo csrf_field(); ?><button class="btn btn-outline-secondary">Importar pedidos</button></form>
<div class="card mt-3"><div class="card-body"><h2 class="h5">Logs recientes</h2><table class="table"><thead><tr><th>Evento</th><th>Estado</th><th>Fecha</th></tr></thead><tbody><?php $__currentLoopData = $recentLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr><td><?php echo e($log->event_type); ?></td><td><?php echo e($log->status); ?></td><td><?php echo e($log->created_at); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></tbody></table></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\integrations\show.blade.php ENDPATH**/ ?>