<?php $__env->startSection('content'); ?>
    <section class="qe-home-hero">
        <div class="container-fluid px-0">
            <div id="homeHero" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner overflow-hidden">
                    <div class="carousel-item active">
                        <a href="<?php echo e(route('store.shop', ['q' => 'Bota con chiporro'])); ?>" class="qe-home-image-slide">
                            <img src="<?php echo e(asset('images/home/slider1.png')); ?>" alt="Cyberday bota con chiporro">
                        </a>
                    </div>
                    <div class="carousel-item">
                        <a href="<?php echo e(route('store.shop', ['q' => 'Crocs con chiporro'])); ?>" class="qe-home-image-slide">
                            <img src="<?php echo e(asset('images/home/slider2.png')); ?>" alt="Cyberday crocs con chiporro">
                        </a>
                    </div>
                    <div class="carousel-item">
                        <a href="<?php echo e(route('store.shop', ['q' => 'Botas de impacto'])); ?>" class="qe-home-image-slide">
                            <img src="<?php echo e(asset('images/home/slider3.png')); ?>" alt="Cyberday botas de impacto">
                        </a>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#homeHero" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                <button class="carousel-control-next" type="button" data-bs-target="#homeHero" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
            </div>
        </div>
    </section>

    <section class="container py-4">
        <div class="row g-3">
            <?php $__currentLoopData = ['Despacho preparado', 'Compra segura', 'Retiro en tienda', 'Ofertas activas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $benefit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-6 col-lg-3">
                    <div class="qe-benefit-pill"><?php echo e($benefit); ?></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>

    <section class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">Categorias destacadas</h2>
            <a href="<?php echo e(route('store.shop')); ?>">Ver todo</a>
        </div>
        <div class="row g-3">
            <?php $__currentLoopData = $featuredCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-6 col-md-4 col-lg-2"><?php if (isset($component)) { $__componentOriginalf36e650d0e73fdd30321018a308bf45c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf36e650d0e73fdd30321018a308bf45c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.category-card','data' => ['category' => $category]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.category-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['category' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($category)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf36e650d0e73fdd30321018a308bf45c)): ?>
<?php $attributes = $__attributesOriginalf36e650d0e73fdd30321018a308bf45c; ?>
<?php unset($__attributesOriginalf36e650d0e73fdd30321018a308bf45c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf36e650d0e73fdd30321018a308bf45c)): ?>
<?php $component = $__componentOriginalf36e650d0e73fdd30321018a308bf45c; ?>
<?php unset($__componentOriginalf36e650d0e73fdd30321018a308bf45c); ?>
<?php endif; ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>

    <section class="container py-4">
        <div class="row g-3">
            <div class="col-md-4">
                <a href="<?php echo e(route('store.shop', ['q' => 'Polerones'])); ?>" class="qe-promo-tile">
                    <img src="<?php echo e(asset('images/home/slider3.png')); ?>" alt="Botas de impacto">
                </a>
            </div>
            <div class="col-md-4">
                <a href="<?php echo e(route('store.shop', ['q' => 'Botas'])); ?>" class="qe-promo-tile">
                    <img src="<?php echo e(asset('images/home/slider1.png')); ?>" alt="Botas con chiporro">
                </a>
            </div>
            <div class="col-md-4">
                <a href="<?php echo e(route('store.shop', ['q' => 'Invierno'])); ?>" class="qe-promo-tile">
                    <img src="<?php echo e(asset('images/home/slider2.png')); ?>" alt="Crocs con chiporro">
                </a>
            </div>
        </div>
    </section>

    <?php $__currentLoopData = [['Productos destacados', $featuredProducts], ['Ofertas vigentes', $offerProducts], ['Ultimos productos', $latestProducts]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$title, $items]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <section class="container py-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 mb-0"><?php echo e($title); ?></h2>
                <a href="<?php echo e(route('store.shop')); ?>">Ver mas</a>
            </div>
            <div class="row g-3 qe-compact-product-grid">
                <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $display): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-6 col-md-4 col-xl-2"><?php if (isset($component)) { $__componentOriginal27ce8c38763e676809739f9c69e6bc06 = $component; } ?>
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
                    <div class="col-12 text-secondary">Sin productos para mostrar.</div>
                <?php endif; ?>
            </div>
        </section>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <section class="qe-banner-band">
        <div class="container">
            <div class="row g-3 align-items-center">
                <div class="col-lg-7">
                    <h2 class="h3">Packs visibles y precios calculados</h2>
                    <p class="mb-0">El stock del pack se calcula desde sus componentes y el precio muestra el ahorro real.</p>
                </div>
                <div class="col-lg-5 text-lg-end"><a href="<?php echo e(route('store.packs.index')); ?>" class="btn btn-dark btn-lg">Explorar packs</a></div>
            </div>
        </div>
    </section>

    <section class="container py-5">
        <h2 class="h4 mb-3">Packs visibles</h2>
        <div class="row g-3">
            <?php $__empty_1 = true; $__currentLoopData = $packs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $display): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-md-3">
                    <a class="card border-0 shadow-sm text-decoration-none text-dark h-100" href="<?php echo e(route('store.packs.show', $display['pack']->slug)); ?>">
                        <div class="ratio ratio-1x1 bg-light"><?php if($display['image']): ?><img src="<?php echo e($display['image']); ?>" class="object-fit-cover" alt="<?php echo e($display['pack']->name); ?>"><?php endif; ?></div>
                        <div class="card-body"><div class="fw-semibold"><?php echo e($display['pack']->name); ?></div><div class="text-danger fw-bold">$<?php echo e(number_format($display['pack_price'], 0, ',', '.')); ?></div><div class="small text-secondary">Ahorro $<?php echo e(number_format($display['saving'], 0, ',', '.')); ?></div></div>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12 text-secondary">Sin packs visibles.</div>
            <?php endif; ?>
        </div>
    </section>

    <section class="container pb-5">
        <div class="qe-newsletter-box">
            <div>
                <h2 class="h4">Recibe novedades y ofertas</h2>
                <p class="text-secondary mb-0">Promociones, nuevos productos y campanas comerciales.</p>
            </div>
            <form method="POST" action="<?php echo e(route('store.newsletter')); ?>" class="d-flex gap-2">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="source" value="home">
                <input type="email" name="email" class="form-control" placeholder="tu@email.cl" required>
                <button class="btn btn-dark">Suscribirme</button>
            </form>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\store\home.blade.php ENDPATH**/ ?>