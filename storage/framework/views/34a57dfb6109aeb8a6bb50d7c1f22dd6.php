<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <?php if (isset($component)) { $__componentOriginal898df52f4a9b89ed7169d35453e99946 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal898df52f4a9b89ed7169d35453e99946 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.breadcrumb','data' => ['items' => [$category->name => route('store.categories.show', $category->slug)]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([$category->name => route('store.categories.show', $category->slug)])]); ?>
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
        <div class="mb-4"><h1 class="h3"><?php echo e($category->name); ?></h1><p class="text-secondary"><?php echo e($category->description); ?></p></div>
        <?php if($category->children->isNotEmpty()): ?><div class="row g-3 mb-4"><?php $__currentLoopData = $category->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="col-md-3"><?php if (isset($component)) { $__componentOriginalf36e650d0e73fdd30321018a308bf45c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf36e650d0e73fdd30321018a308bf45c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.category-card','data' => ['category' => $child]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.category-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['category' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($child)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf36e650d0e73fdd30321018a308bf45c)): ?>
<?php $attributes = $__attributesOriginalf36e650d0e73fdd30321018a308bf45c; ?>
<?php unset($__attributesOriginalf36e650d0e73fdd30321018a308bf45c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf36e650d0e73fdd30321018a308bf45c)): ?>
<?php $component = $__componentOriginalf36e650d0e73fdd30321018a308bf45c; ?>
<?php unset($__componentOriginalf36e650d0e73fdd30321018a308bf45c); ?>
<?php endif; ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div><?php endif; ?>
        <div class="row g-4"><aside class="col-lg-3"><?php if (isset($component)) { $__componentOriginal338003041f651f41ab3e2c773020e6b4 = $component; } ?>
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
<?php endif; ?></aside><section class="col-lg-9"><div class="row g-3"><?php $__empty_1 = true; $__currentLoopData = $presentedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $display): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div class="col-sm-6 col-xl-4"><?php if (isset($component)) { $__componentOriginal27ce8c38763e676809739f9c69e6bc06 = $component; } ?>
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
<?php endif; ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="col-12 text-secondary">Sin productos en esta categoría.</div><?php endif; ?></div><?php if (isset($component)) { $__componentOriginal69d3273121656a301ec40d10dc76342e = $component; } ?>
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
<?php endif; ?></section></div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\store\category.blade.php ENDPATH**/ ?>