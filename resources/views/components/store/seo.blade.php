@php($seo = $seo ?? [])
<title>{{ $seo['title'] ?? 'Qué Económico' }}</title>
<meta name="description" content="{{ $seo['description'] ?? 'Tienda online Qué Económico' }}">
<link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">
<meta property="og:title" content="{{ $seo['title'] ?? 'Qué Económico' }}">
<meta property="og:description" content="{{ $seo['description'] ?? 'Tienda online Qué Económico' }}">
<meta property="og:url" content="{{ $seo['canonical'] ?? url()->current() }}">
<meta property="og:type" content="{{ isset($seo['schemaProduct']) ? 'product' : 'website' }}">
@if(!empty($seo['image']))<meta property="og:image" content="{{ url($seo['image']) }}">@endif
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seo['title'] ?? 'Qué Económico' }}">
<meta name="twitter:description" content="{{ $seo['description'] ?? 'Tienda online Qué Económico' }}">
@if(!empty($seo['schemaProduct']))
    @php($display = $seo['schemaProduct'])
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
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
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endif
