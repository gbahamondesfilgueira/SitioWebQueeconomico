<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <?php if (isset($component)) { $__componentOriginal898df52f4a9b89ed7169d35453e99946 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal898df52f4a9b89ed7169d35453e99946 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.breadcrumb','data' => ['items' => ['Packs' => route('store.packs.index'), $pack->name => route('store.packs.show', $pack->slug)]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Packs' => route('store.packs.index'), $pack->name => route('store.packs.show', $pack->slug)])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal898df52f4a9b89ed7169d35453e99946)): ?>
<?php $attributes = $__attributesOriginal898df52f4a9b89ed7169d35453e99946; ?>
<?php unset($__attributesOriginal898df52f4a9b89ed7169d35453e99946); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal898df52f4a9b89ed7169d35453e99946)): ?>
<?php $component = $__componentOriginal898df52f4a9b89ed7169d35453e99946; ?>
<?php unset($__componentOriginal898df52f4a9b89ed7169d35453e99946); ?>
<?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="ratio ratio-1x1 bg-light rounded shadow-sm">
                    <?php if($display['image']): ?>
                        <img src="<?php echo e($display['image']); ?>" class="object-fit-cover" alt="<?php echo e($pack->name); ?>">
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-7">
                <h1><?php echo e($pack->name); ?></h1>
                <p class="text-secondary"><?php echo e($pack->description); ?></p>
                <div class="mb-2">
                    <span class="text-decoration-line-through text-secondary">$<?php echo e(number_format($display['normal_price'], 0, ',', '.')); ?></span>
                </div>
                <div class="display-6 fw-bold text-danger">$<?php echo e(number_format($display['pack_price'], 0, ',', '.')); ?></div>
                <div class="mb-3">
                    Ahorras $<?php echo e(number_format($display['saving'], 0, ',', '.')); ?>

                    <?php if($display['saving_percentage']): ?>
                        (<?php echo e($display['saving_percentage']); ?>%)
                    <?php endif; ?>
                </div>
                <span class="badge <?php echo e($display['stock'] <= 0 ? 'text-bg-secondary' : ($display['stock'] <= 5 ? 'text-bg-warning' : 'text-bg-success')); ?>"><?php echo e($display['stock_label']); ?></span>

                <form method="POST" action="<?php echo e(route('store.cart.add')); ?>" class="mt-3" data-cart-add-form>
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="item_type" value="pack">
                    <input type="hidden" name="product_pack_id" value="<?php echo e($pack->id); ?>">
                    <div class="input-group">
                        <input type="number" name="quantity" value="1" min="1" step="1" class="form-control">
                        <button class="btn btn-dark btn-lg" <?php if($display['stock'] <= 0): echo 'disabled'; endif; ?>>Agregar pack</button>
                    </div>
                </form>
            </div>
        </div>

        <section class="mt-5">
            <h2 class="h4">Productos incluidos</h2>
            <div class="row g-3">
                <?php $__currentLoopData = $pack->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="fw-semibold"><?php echo e($item->product?->name); ?></div>
                                <div class="small text-secondary"><?php echo e($item->variant?->name); ?> · Cantidad: <?php echo e((int) $item->quantity); ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\store\packs\show.blade.php ENDPATH**/ ?>