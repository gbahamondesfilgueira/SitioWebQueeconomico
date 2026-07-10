<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h1 class="h3 mb-1">Resultados de busqueda</h1>
                <p class="text-secondary mb-0"><?php echo e($products->total()); ?> resultado(s) para "<?php echo e($query); ?>"</p>
            </div>
            <button class="btn btn-outline-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#searchFilters" aria-controls="searchFilters">
                Filtros
            </button>
        </div>

        <div class="row g-4">
            <aside class="col-lg-3 d-none d-lg-block">
                <?php if (isset($component)) { $__componentOriginal338003041f651f41ab3e2c773020e6b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal338003041f651f41ab3e2c773020e6b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.filter-sidebar','data' => ['categories' => $categories,'brands' => $brands,'originCountries' => $originCountries]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.filter-sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['categories' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categories),'brands' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($brands),'origin-countries' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($originCountries)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal338003041f651f41ab3e2c773020e6b4)): ?>
<?php $attributes = $__attributesOriginal338003041f651f41ab3e2c773020e6b4; ?>
<?php unset($__attributesOriginal338003041f651f41ab3e2c773020e6b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal338003041f651f41ab3e2c773020e6b4)): ?>
<?php $component = $__componentOriginal338003041f651f41ab3e2c773020e6b4; ?>
<?php unset($__componentOriginal338003041f651f41ab3e2c773020e6b4); ?>
<?php endif; ?>
            </aside>
            <section class="col-lg-9">
                <div class="row row-cols-2 row-cols-md-4 row-cols-xl-5 g-3 qe-shop-product-grid">
                    <?php $__empty_1 = true; $__currentLoopData = $presentedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $display): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="col"><?php if (isset($component)) { $__componentOriginal27ce8c38763e676809739f9c69e6bc06 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal27ce8c38763e676809739f9c69e6bc06 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.product-card','data' => ['display' => $display]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.product-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['display' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($display)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal27ce8c38763e676809739f9c69e6bc06)): ?>
<?php $attributes = $__attributesOriginal27ce8c38763e676809739f9c69e6bc06; ?>
<?php unset($__attributesOriginal27ce8c38763e676809739f9c69e6bc06); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal27ce8c38763e676809739f9c69e6bc06)): ?>
<?php $component = $__componentOriginal27ce8c38763e676809739f9c69e6bc06; ?>
<?php unset($__componentOriginal27ce8c38763e676809739f9c69e6bc06); ?>
<?php endif; ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-12"><div class="alert alert-light border">No encontramos productos para tu busqueda.</div></div>
                    <?php endif; ?>
                </div>
                <?php if (isset($component)) { $__componentOriginal69d3273121656a301ec40d10dc76342e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69d3273121656a301ec40d10dc76342e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.pagination','data' => ['items' => $products]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($products)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69d3273121656a301ec40d10dc76342e)): ?>
<?php $attributes = $__attributesOriginal69d3273121656a301ec40d10dc76342e; ?>
<?php unset($__attributesOriginal69d3273121656a301ec40d10dc76342e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69d3273121656a301ec40d10dc76342e)): ?>
<?php $component = $__componentOriginal69d3273121656a301ec40d10dc76342e; ?>
<?php unset($__componentOriginal69d3273121656a301ec40d10dc76342e); ?>
<?php endif; ?>
            </section>
        </div>
    </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="searchFilters" aria-labelledby="searchFiltersLabel">
        <div class="offcanvas-header">
            <h2 class="h5 mb-0" id="searchFiltersLabel">Filtros</h2>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body">
            <?php if (isset($component)) { $__componentOriginal338003041f651f41ab3e2c773020e6b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal338003041f651f41ab3e2c773020e6b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.filter-sidebar','data' => ['categories' => $categories,'brands' => $brands,'originCountries' => $originCountries,'submitLabel' => 'Filtrar']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.filter-sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['categories' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categories),'brands' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($brands),'origin-countries' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($originCountries),'submit-label' => 'Filtrar']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal338003041f651f41ab3e2c773020e6b4)): ?>
<?php $attributes = $__attributesOriginal338003041f651f41ab3e2c773020e6b4; ?>
<?php unset($__attributesOriginal338003041f651f41ab3e2c773020e6b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal338003041f651f41ab3e2c773020e6b4)): ?>
<?php $component = $__componentOriginal338003041f651f41ab3e2c773020e6b4; ?>
<?php unset($__componentOriginal338003041f651f41ab3e2c773020e6b4); ?>
<?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\store\search.blade.php ENDPATH**/ ?>