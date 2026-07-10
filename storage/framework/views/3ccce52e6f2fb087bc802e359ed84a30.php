<?php $__env->startSection('title', 'Terminales POS'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="h4">Terminales POS</h1><a class="btn btn-primary" href="<?php echo e(route('admin.pos.terminals.create')); ?>">Crear terminal</a></div>
<div class="card"><div class="card-body table-responsive"><table class="table align-middle"><thead><tr><th>Nombre</th><th>Código</th><th>Bodega</th><th>Estado</th><th></th></tr></thead><tbody>
<?php $__empty_1 = true; $__currentLoopData = $terminals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $terminal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr><td><?php echo e($terminal->name); ?></td><td><?php echo e($terminal->code); ?></td><td><?php echo e($terminal->warehouse?->name); ?></td><td><span class="badge bg-<?php echo e($terminal->is_active ? 'success' : 'secondary'); ?>"><?php echo e($terminal->is_active ? 'Activo' : 'Inactivo'); ?></span></td><td class="text-end"><a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.pos.terminals.edit', $terminal)); ?>">Editar</a><form method="POST" action="<?php echo e(route('admin.pos.terminals.toggle', $terminal)); ?>" class="d-inline"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><button class="btn btn-sm btn-outline-secondary">Activar/desactivar</button></form></td></tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <tr><td colspan="5" class="text-muted">Sin terminales.</td></tr> <?php endif; ?>
</tbody></table><?php echo e($terminals->links()); ?></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\pos\terminals\index.blade.php ENDPATH**/ ?>