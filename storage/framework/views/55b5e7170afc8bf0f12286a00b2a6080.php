<?php $__env->startSection('title', 'Etiquetas cliente'); ?>
<?php $__env->startSection('page-title', 'Etiquetas cliente'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row g-3">
        <div class="col-lg-4">
            <form method="POST" action="<?php echo e(route('admin.customer-tags.store')); ?>" class="card border-0 shadow-sm">
                <?php echo csrf_field(); ?>
                <div class="card-header bg-white fw-semibold">Nueva etiqueta</div>
                <div class="card-body">
                    <label class="form-label">Nombre</label><input name="name" class="form-control mb-2" required>
                    <label class="form-label">Descripción</label><input name="description" class="form-control mb-2">
                    <label class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" checked> Activa</label>
                </div>
                <div class="card-footer bg-white"><button class="btn btn-dark">Crear</button></div>
            </form>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive"><table class="table mb-0 align-middle"><thead><tr><th>Etiqueta</th><th>Clientes</th><th>Estado</th><th>Editar rápido</th></tr></thead><tbody>
                    <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($tag->name); ?></td><td><?php echo e($tag->customer_profiles_count); ?></td><td><?php echo e($tag->is_active ? 'Activa' : 'Inactiva'); ?></td>
                            <td><form method="POST" action="<?php echo e(route('admin.customer-tags.update', $tag)); ?>" class="row g-2"><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?><div class="col"><input name="name" class="form-control form-control-sm" value="<?php echo e($tag->name); ?>"></div><div class="col"><input name="description" class="form-control form-control-sm" value="<?php echo e($tag->description); ?>"></div><div class="col-auto"><label class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" <?php if($tag->is_active): echo 'checked'; endif; ?>></label></div><div class="col-auto"><button class="btn btn-sm btn-outline-primary">Guardar</button></div></form></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody></table></div>
                <div class="card-footer bg-white"><?php echo e($tags->links()); ?></div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\customer_tags\index.blade.php ENDPATH**/ ?>