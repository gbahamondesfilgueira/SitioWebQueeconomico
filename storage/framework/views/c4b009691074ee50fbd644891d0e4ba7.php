<?php ($seo = $seo ?? []); ?>
<title><?php echo e($seo['title'] ?? 'Qué Económico'); ?></title>
<meta name="description" content="<?php echo e($seo['description'] ?? 'Tienda online Qué Económico'); ?>">
<link rel="canonical" href="<?php echo e($seo['canonical'] ?? url()->current()); ?>">
<meta property="og:title" content="<?php echo e($seo['title'] ?? 'Qué Económico'); ?>">
<meta property="og:description" content="<?php echo e($seo['description'] ?? 'Tienda online Qué Económico'); ?>">
<meta property="og:url" content="<?php echo e($seo['canonical'] ?? url()->current()); ?>">
<meta property="og:type" content="<?php echo e(isset($seo['schemaProduct']) ? 'product' : 'website'); ?>">
<?php if(!empty($seo['image'])): ?><meta property="og:image" content="<?php echo e(url($seo['image'])); ?>"><?php endif; ?>
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo e($seo['title'] ?? 'Qué Económico'); ?>">
<meta name="twitter:description" content="<?php echo e($seo['description'] ?? 'Tienda online Qué Económico'); ?>">
<?php if(!empty($seo['schemaProduct'])): ?>
    <?php ($display = $seo['schemaProduct']); ?>
    <script type="application/ld+json">
        <?php echo json_encode([
            '<?php $__contextArgs = [];
if (context()->has($__contextArgs[0])) :
if (isset($value)) { $__contextPrevious[] = $value; }
$value = context()->get($__contextArgs[0]); ?>' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $display['product']->name,
            'description' => $display['product']->short_description,
            'sku' => $display['variant']?->sku ?? $display['product']->sku,
            'brand' => ['@type' => 'Brand', 'name' => $display['product']->brand?->name],
            'image' => $display['image'] ? [url($display['image'])] : [],
            'offers' => [
                '@type' => 'Offer',
                'priceCurrency' => 'CLP',
                'price' => $display['final_price'],
                'availability' => $display['stock'] > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => url()->current(),
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>

    </script>
<?php endif; ?>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\components\store\seo.blade.php ENDPATH**/ ?>