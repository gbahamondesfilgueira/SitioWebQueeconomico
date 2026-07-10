<?php $__env->startSection('title', 'Editar producto'); ?>
<?php $__env->startSection('page-title', 'Editar producto'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-end gap-2 mb-3">
        <a class="btn btn-outline-secondary" href="<?php echo e(route('admin.products.show', $product)); ?>">Ver ficha</a>
        <a class="btn btn-outline-success" href="<?php echo e(route('admin.stock-adjustments.create', ['product_id' => $product->id])); ?>">Agregar stock</a>
        <?php if($product->product_type === 'variable'): ?>
            <a class="btn btn-outline-primary" href="<?php echo e(route('admin.products.variants.index', $product)); ?>">Gestionar variantes</a>
        <?php endif; ?>
    </div>
    <div class="alert alert-info">
        Para ingresar stock: guarda el producto, luego entra a <strong>Agregar stock</strong> y crea un ajuste de inventario aprobado.
        Si el producto es variable, primero crea sus variantes.
    </div>
    <form method="POST" action="<?php echo e(route('admin.products.update', $product)); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <?php echo $__env->make('admin.products.partials.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\products\edit.blade.php ENDPATH**/ ?>