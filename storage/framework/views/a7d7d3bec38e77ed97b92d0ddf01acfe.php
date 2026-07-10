<?php $__env->startSection('title', 'Clientes'); ?>
<?php $__env->startSection('page-title', 'CRM de Clientes'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row g-3 mb-4">
        <?php $__currentLoopData = [['Registrados', $stats['total']], ['Activos', $stats['active']], ['Nuevos', $stats['new']], ['VIP', $stats['vip']], ['Empresas', $stats['companies']], ['Newsletter', $stats['newsletter']], ['Inactivos', $stats['inactive']]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-2">
                <div class="card border-0 shadow-sm"><div class="card-body"><div class="text-secondary small"><?php echo e($label); ?></div><div class="fs-4 fw-bold"><?php echo e($value); ?></div></div></div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex gap-2 align-items-center">
            <form class="d-flex gap-2 flex-grow-1">
                <input class="form-control" name="search" value="<?php echo e($search); ?>" placeholder="Buscar por nombre, email, RUT o empresa">
                <button class="btn btn-outline-secondary">Buscar</button>
            </form>
            <a href="<?php echo e(route('admin.customers.create')); ?>" class="btn btn-dark"><i class="bi bi-plus-lg me-1"></i>Crear cliente</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th>Cliente</th><th>Tipo</th><th>Contacto</th><th>Etiquetas</th><th>Puntos</th><th>Estado</th><th></th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><div class="fw-semibold"><?php echo e($customer->display_name); ?></div><div class="small text-secondary"><?php echo e($customer->rut); ?></div></td>
                            <td><?php echo e($customer->customer_type === 'company' ? 'Empresa' : 'Particular'); ?></td>
                            <td><div><?php echo e($customer->email); ?></div><div class="small text-secondary"><?php echo e($customer->phone); ?></div></td>
                            <td><?php $__currentLoopData = $customer->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span class="badge text-bg-light"><?php echo e($tag->name); ?></span> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></td>
                            <td><?php echo e(number_format((float) $customer->reward_points, 0, ',', '.')); ?></td>
                            <td><span class="badge <?php echo e($customer->is_active ? 'text-bg-success' : 'text-bg-secondary'); ?>"><?php echo e($customer->is_active ? 'Activo' : 'Inactivo'); ?></span></td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(route('admin.customers.show', $customer)); ?>">Ver</a>
                                <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.customers.edit', $customer)); ?>">Editar</a>
                                <form class="d-inline" method="POST" action="<?php echo e(route('admin.customers.toggle-active', $customer)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><button class="btn btn-sm btn-outline-warning"><?php echo e($customer->is_active ? 'Desactivar' : 'Activar'); ?></button></form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center text-secondary py-4">No hay clientes registrados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white"><?php echo e($customers->links()); ?></div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\customers\index.blade.php ENDPATH**/ ?>