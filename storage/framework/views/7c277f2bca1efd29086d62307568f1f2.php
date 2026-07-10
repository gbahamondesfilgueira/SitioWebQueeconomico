<?php $__env->startSection('title',$warehouse->name); ?>
<?php $__env->startSection('page-title','Detalle de bodega'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between mb-3"><h1 class="h4"><?php echo e($warehouse->name); ?></h1><div class="d-flex gap-2"><a class="btn btn-outline-secondary" href="<?php echo e(route('admin.warehouses.index')); ?>">Volver</a><a class="btn btn-primary" href="<?php echo e(route('admin.warehouses.locations.index',$warehouse)); ?>">Ubicaciones</a></div></div>
<div class="card border-0 shadow-sm"><div class="card-body"><dl class="row mb-0"><dt class="col-sm-3">Código</dt><dd class="col-sm-9"><?php echo e($warehouse->code); ?></dd><dt class="col-sm-3">Tipo</dt><dd class="col-sm-9"><?php echo e($warehouse->type); ?></dd><dt class="col-sm-3">Dirección</dt><dd class="col-sm-9"><?php echo e($warehouse->address ?? '-'); ?></dd><dt class="col-sm-3">Estado</dt><dd class="col-sm-9"><?php echo e($warehouse->is_active ? 'Activa':'Inactiva'); ?></dd></dl></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\warehouses\show.blade.php ENDPATH**/ ?>