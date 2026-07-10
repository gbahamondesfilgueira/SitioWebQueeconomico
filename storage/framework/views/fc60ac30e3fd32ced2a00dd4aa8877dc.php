<?php $__env->startSection('content'); ?>
    <?php
        $allowedProductHtml = '<p><br><strong><b><em><i><ul><ol><li><h2><h3><h4><table><thead><tbody><tr><th><td>';
        $sanitizeProductHtml = function (?string $html) use ($allowedProductHtml): string {
            $clean = strip_tags($html ?? '', $allowedProductHtml);
            $clean = preg_replace('/\s(on\w+|style)\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $clean);
            return preg_replace('/javascript\s*:/i', '', $clean);
        };
        $rawLongDescription = str_replace('\n', "\n", $product->long_description ?? '');
        $rawTechnicalDescription = str_replace('\n', "\n", $product->technical_description ?? '');

        if (blank($rawTechnicalDescription) && str_contains(\Illuminate\Support\Str::lower($rawLongDescription), '<table')) {
            $lowerDescription = \Illuminate\Support\Str::lower($rawLongDescription);
            $tablePosition = strpos($lowerDescription, '<table');
            $headingPosition = strrpos(substr($lowerDescription, 0, $tablePosition), '<h');
            $splitPosition = $headingPosition !== false ? $headingPosition : $tablePosition;
            $rawTechnicalDescription = trim(substr($rawLongDescription, $splitPosition));
            $rawLongDescription = trim(substr($rawLongDescription, 0, $splitPosition));
        }

        $shortDescription = $sanitizeProductHtml(str_replace('\n', "\n", $product->short_description ?? ''));
        $longDescription = $sanitizeProductHtml($rawLongDescription);
        $technicalDescription = $sanitizeProductHtml($rawTechnicalDescription);
    ?>

    <div class="container py-4">
        <?php if (isset($component)) { $__componentOriginal898df52f4a9b89ed7169d35453e99946 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal898df52f4a9b89ed7169d35453e99946 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.breadcrumb','data' => ['items' => [$product->category?->name ?? 'Categoria' => $product->category ? route('store.categories.show', $product->category->slug) : route('store.shop'), $product->name => route('store.products.show', $product->slug)],'mobile' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([$product->category?->name ?? 'Categoria' => $product->category ? route('store.categories.show', $product->category->slug) : route('store.shop'), $product->name => route('store.products.show', $product->slug)]),'mobile' => true]); ?>
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
            <div class="col-lg-6">
                <?php if (isset($component)) { $__componentOriginalf906c871defd0e34f278fe20e8632ab7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf906c871defd0e34f278fe20e8632ab7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.product-gallery','data' => ['product' => $product,'display' => $display]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.product-gallery'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product),'display' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($display)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf906c871defd0e34f278fe20e8632ab7)): ?>
<?php $attributes = $__attributesOriginalf906c871defd0e34f278fe20e8632ab7; ?>
<?php unset($__attributesOriginalf906c871defd0e34f278fe20e8632ab7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf906c871defd0e34f278fe20e8632ab7)): ?>
<?php $component = $__componentOriginalf906c871defd0e34f278fe20e8632ab7; ?>
<?php unset($__componentOriginalf906c871defd0e34f278fe20e8632ab7); ?>
<?php endif; ?>
            </div>
            <div class="col-lg-6">
                <div class="small text-secondary"><?php echo e($product->brand?->name); ?></div>
                <h1 class="h2"><?php echo e($product->name); ?></h1>
                <div class="mb-2">
                    <span class="badge <?php echo e($display['stock_class']); ?>" data-product-stock-badge><?php echo e($display['stock_label']); ?></span>
                    <?php if($product->is_featured): ?><span class="badge text-bg-warning">Destacado</span><?php endif; ?>
                </div>
                <?php if (isset($component)) { $__componentOriginal067ed8b6e5ef0df3ae9aa247904fdf86 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal067ed8b6e5ef0df3ae9aa247904fdf86 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.price','data' => ['display' => $display]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.price'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['display' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($display)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal067ed8b6e5ef0df3ae9aa247904fdf86)): ?>
<?php $attributes = $__attributesOriginal067ed8b6e5ef0df3ae9aa247904fdf86; ?>
<?php unset($__attributesOriginal067ed8b6e5ef0df3ae9aa247904fdf86); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal067ed8b6e5ef0df3ae9aa247904fdf86)): ?>
<?php $component = $__componentOriginal067ed8b6e5ef0df3ae9aa247904fdf86; ?>
<?php unset($__componentOriginal067ed8b6e5ef0df3ae9aa247904fdf86); ?>
<?php endif; ?>
                <?php if($product->sale_ends_at && $display['discount_percentage'] > 0): ?>
                    <div class="small text-danger mt-1">Oferta hasta <?php echo e($product->sale_ends_at->format('d/m/Y H:i')); ?></div>
                <?php endif; ?>

                <div class="my-3">
                    <?php if (isset($component)) { $__componentOriginal4fea6c60e3289f654dca4c953cc60ccf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4fea6c60e3289f654dca4c953cc60ccf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.variant-selector','data' => ['variants' => $display['variants']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.variant-selector'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variants' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($display['variants'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4fea6c60e3289f654dca4c953cc60ccf)): ?>
<?php $attributes = $__attributesOriginal4fea6c60e3289f654dca4c953cc60ccf; ?>
<?php unset($__attributesOriginal4fea6c60e3289f654dca4c953cc60ccf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4fea6c60e3289f654dca4c953cc60ccf)): ?>
<?php $component = $__componentOriginal4fea6c60e3289f654dca4c953cc60ccf; ?>
<?php unset($__componentOriginal4fea6c60e3289f654dca4c953cc60ccf); ?>
<?php endif; ?>
                </div>

                <form method="POST" action="<?php echo e(route('store.cart.add')); ?>" class="mb-3" data-cart-add-form data-product-purchase-form>
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="item_type" value="product">
                    <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                    <?php if($product->product_type === 'variable'): ?>
                        <select name="product_variant_id" class="form-select mb-2" required data-product-variant-select>
                            <?php $__currentLoopData = $display['variants']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($variant['id']); ?>" data-stock="<?php echo e($variant['stock']); ?>" data-stock-label="<?php echo e($variant['stock_label']); ?>" <?php if($variant['stock'] < 1): echo 'disabled'; endif; ?>>
                                    <?php echo e($variant['name']); ?> - $<?php echo e(number_format($variant['final_price'], 0, ',', '.')); ?> - <?php echo e($variant['stock_label']); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    <?php endif; ?>
                    <div class="input-group">
                        <input type="number" name="quantity" class="form-control" value="1" min="1" step="1">
                        <button class="btn btn-dark btn-lg" data-product-add-button <?php if($display['stock'] <= 0): echo 'disabled'; endif; ?>>Agregar al carrito</button>
                    </div>
                </form>

                <dl class="row small">
                    <dt class="col-4">SKU</dt><dd class="col-8"><?php echo e($product->sku ?: '-'); ?></dd>
                    <dt class="col-4">Código barras</dt><dd class="col-8"><?php echo e($product->barcode ?: '-'); ?></dd>
                    <dt class="col-4">País origen</dt><dd class="col-8"><?php echo e($product->originCountry?->name ?: '-'); ?></dd>
                    <dt class="col-4">Peso</dt><dd class="col-8"><?php echo e($product->weight ?: '-'); ?> <?php echo e($product->weightUnit?->code); ?></dd>
                    <dt class="col-4">Medidas</dt><dd class="col-8"><?php echo e($product->height); ?> x <?php echo e($product->width); ?> x <?php echo e($product->length); ?> <?php echo e($product->dimensionUnit?->code); ?></dd>
                </dl>

                <div class="d-flex gap-2 flex-wrap">
                    <?php $__currentLoopData = $product->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span class="badge text-bg-light"><?php echo e($tag->name); ?></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="small text-secondary mt-3">Compartir: Facebook · Instagram · WhatsApp</div>
            </div>
        </div>

        <section class="mt-5">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#desc" type="button">Descripción</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tech" type="button">Ficha técnica</button></li>
            </ul>
            <div class="tab-content border border-top-0 p-3 bg-white">
                <div id="desc" class="tab-pane fade show active product-rich-text">
                    <?php if($shortDescription): ?><div class="mb-3"><?php echo $shortDescription; ?></div><?php endif; ?>
                    <?php echo $longDescription ?: '<p class="text-secondary mb-0">Sin descripción disponible.</p>'; ?>

                </div>
                <div id="tech" class="tab-pane fade product-rich-text product-technical-sheet">
                    <?php echo $technicalDescription ?: '<p class="text-secondary mb-0">Sin ficha técnica disponible.</p>'; ?>

                </div>
            </div>
        </section>

        <section class="mt-5">
            <h2 class="h4">Productos relacionados</h2>
            <div class="row g-3">
                <?php $__empty_1 = true; $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $display): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-sm-6 col-lg-3"><?php if (isset($component)) { $__componentOriginal27ce8c38763e676809739f9c69e6bc06 = $component; } ?>
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
                    <div class="text-secondary">Sin relacionados por ahora.</div>
                <?php endif; ?>
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\store\products\show.blade.php ENDPATH**/ ?>