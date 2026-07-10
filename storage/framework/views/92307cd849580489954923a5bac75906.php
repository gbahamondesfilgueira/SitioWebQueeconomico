<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <?php if (isset($component)) { $__componentOriginal898df52f4a9b89ed7169d35453e99946 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal898df52f4a9b89ed7169d35453e99946 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.breadcrumb','data' => ['items' => ['Tienda' => route('store.shop')]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Tienda' => route('store.shop')])]); ?>
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

        <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
            <h1 class="h3 mb-0">Tienda</h1>

            <div class="d-flex gap-2">
                <button class="btn btn-outline-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#storeFilters" aria-controls="storeFilters">
                    Filtros
                </button>

                <form>
                    <?php $__currentLoopData = request()->except('sort', 'page'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(is_array($value)): ?>
                            <?php $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="hidden" name="<?php echo e($key); ?>[]" value="<?php echo e($item); ?>">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e($value); ?>">
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="recent">Mas recientes</option>
                        <option value="price_asc" <?php if(request('sort') === 'price_asc'): echo 'selected'; endif; ?>>Menor precio</option>
                        <option value="price_desc" <?php if(request('sort') === 'price_desc'): echo 'selected'; endif; ?>>Mayor precio</option>
                        <option value="name_asc" <?php if(request('sort') === 'name_asc'): echo 'selected'; endif; ?>>Nombre A-Z</option>
                        <option value="name_desc" <?php if(request('sort') === 'name_desc'): echo 'selected'; endif; ?>>Nombre Z-A</option>
                        <option value="featured" <?php if(request('sort') === 'featured'): echo 'selected'; endif; ?>>Destacados</option>
                    </select>
                </form>
            </div>
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
                        <div class="col">
                            <?php if (isset($component)) { $__componentOriginal27ce8c38763e676809739f9c69e6bc06 = $component; } ?>
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
<?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-12">
                            <div class="alert alert-light border">No hay productos con esos filtros.</div>
                        </div>
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

    <div class="offcanvas offcanvas-start" tabindex="-1" id="storeFilters" aria-labelledby="storeFiltersLabel">
        <div class="offcanvas-header">
            <h2 class="h5 mb-0" id="storeFiltersLabel">Filtros</h2>
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

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\store\shop.blade.php ENDPATH**/ ?>