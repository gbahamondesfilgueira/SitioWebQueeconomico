<?php $__env->startSection('title','API Client'); ?>
<?php $__env->startSection('content'); ?>
<h1 class="h4"><?php echo e($client->name); ?></h1>
<?php if($plainToken): ?><div class="alert alert-warning"><strong>Token visible solo una vez:</strong><br><code><?php echo e($plainToken); ?></code></div><?php endif; ?>
<div class="card"><div class="card-body"><p><strong>Código:</strong> <?php echo e($client->code); ?></p><p><strong>Activo:</strong> <?php echo e($client->is_active ? 'Sí' : 'No'); ?></p><p><strong>Permisos:</strong> <?php echo e(implode(', ', $client->permissions ?? [])); ?></p><form method="POST" action="<?php echo e(route('admin.api-clients.revoke',$client)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><button class="btn btn-danger">Revocar</button></form></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\integrations\api_clients\show.blade.php ENDPATH**/ ?>