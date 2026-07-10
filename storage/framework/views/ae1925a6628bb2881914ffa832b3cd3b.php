<?php $__env->startSection('title', 'Subir productos'); ?>
<?php $__env->startSection('page-title', 'Subir productos'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form method="POST" action="<?php echo e(route('admin.imports.products.store')); ?>" enctype="multipart/form-data" class="card border-0 shadow-sm">
                <?php echo csrf_field(); ?>
                <div class="card-body">
                    <h1 class="h4">Subir CSV WooCommerce</h1>
                    <p class="text-secondary">Formato soportado: exportaci&oacute;n de productos WooCommerce en espa&ntilde;ol. El importador crea o actualiza productos por SKU, crea maestros relacionados cuando no existan y carga stock inicial si el CSV trae inventario.</p>

                    <div class="mb-3">
                        <label class="form-label">Archivo CSV</label>
                        <input type="file" name="file" accept=".csv,text/csv" class="form-control" required>
                        <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="alert alert-info mb-0">
                        Se importar&aacute;: productos simples, productos variables, variantes, categor&iacute;as, marcas, etiquetas, atributos, precios, descripciones, SKU, c&oacute;digos de barra, peso, medidas, im&aacute;genes por URL y stock inicial en Bodega Principal cuando corresponda.
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between">
                    <a href="<?php echo e(route('admin.imports.products.index')); ?>" class="btn btn-outline-secondary">Volver</a>
                    <button class="btn btn-dark">Procesar importaci&oacute;n</button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\imports\products\create.blade.php ENDPATH**/ ?>