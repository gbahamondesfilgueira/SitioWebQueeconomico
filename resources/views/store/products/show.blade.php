@extends('layouts.store')

@section('content')
    @php
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
    @endphp

    <div class="container py-4">
        <x-store.breadcrumb :items="[$product->category?->name ?? 'Categoria' => $product->category ? route('store.categories.show', $product->category->slug) : route('store.shop'), $product->name => route('store.products.show', $product->slug)]" mobile />

        <div class="row g-4">
            <div class="col-lg-6">
                <x-store.product-gallery :product="$product" :display="$display" />
            </div>
            <div class="col-lg-6">
                <div class="small text-secondary">{{ $product->brand?->name }}</div>
                <h1 class="h2">{{ $product->name }}</h1>
                <div class="mb-2">
                    <span class="badge {{ $display['stock_class'] }}" data-product-stock-badge>{{ $display['stock_label'] }}</span>
                    @if($product->is_featured)<span class="badge text-bg-warning">Destacado</span>@endif
                </div>
                <x-store.price :display="$display" />
                @if($product->sale_ends_at && $display['discount_percentage'] > 0)
                    <div class="small text-danger mt-1">Oferta hasta {{ $product->sale_ends_at->format('d/m/Y H:i') }}</div>
                @endif

                <div class="my-3">
                    <x-store.variant-selector :variants="$display['variants']" />
                </div>

                <form method="POST" action="{{ route('store.cart.add') }}" class="mb-3" data-cart-add-form data-product-purchase-form>
                    @csrf
                    <input type="hidden" name="item_type" value="product">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    @if($product->product_type === 'variable')
                        <select name="product_variant_id" class="form-select mb-2" required data-product-variant-select>
                            @foreach($display['variants'] as $variant)
                                <option value="{{ $variant['id'] }}" data-stock="{{ $variant['stock'] }}" data-stock-label="{{ $variant['stock_label'] }}" @disabled($variant['stock'] < 1)>
                                    {{ $variant['name'] }} - ${{ number_format($variant['final_price'], 0, ',', '.') }} - {{ $variant['stock_label'] }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                    <div class="input-group">
                        <input type="number" name="quantity" class="form-control" value="1" min="1" step="1">
                        <button class="btn btn-dark btn-lg" data-product-add-button @disabled($display['stock'] <= 0)>Agregar al carrito</button>
                    </div>
                </form>

                <dl class="row small">
                    <dt class="col-4">SKU</dt><dd class="col-8">{{ $product->sku ?: '-' }}</dd>
                    <dt class="col-4">Código barras</dt><dd class="col-8">{{ $product->barcode ?: '-' }}</dd>
                    <dt class="col-4">País origen</dt><dd class="col-8">{{ $product->originCountry?->name ?: '-' }}</dd>
                    <dt class="col-4">Peso</dt><dd class="col-8">{{ $product->weight ?: '-' }} {{ $product->weightUnit?->code }}</dd>
                    <dt class="col-4">Medidas</dt><dd class="col-8">{{ $product->height }} x {{ $product->width }} x {{ $product->length }} {{ $product->dimensionUnit?->code }}</dd>
                </dl>

                <div class="d-flex gap-2 flex-wrap">
                    @foreach($product->tags as $tag)<span class="badge text-bg-light">{{ $tag->name }}</span>@endforeach
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
                    @if($shortDescription)<div class="mb-3">{!! $shortDescription !!}</div>@endif
                    {!! $longDescription ?: '<p class="text-secondary mb-0">Sin descripción disponible.</p>' !!}
                </div>
                <div id="tech" class="tab-pane fade product-rich-text product-technical-sheet">
                    {!! $technicalDescription ?: '<p class="text-secondary mb-0">Sin ficha técnica disponible.</p>' !!}
                </div>
            </div>
        </section>

        <section class="mt-5">
            <h2 class="h4">Productos relacionados</h2>
            <div class="row g-3">
                @forelse($related as $display)
                    <div class="col-sm-6 col-lg-3"><x-store.product-card :display="$display" /></div>
                @empty
                    <div class="text-secondary">Sin relacionados por ahora.</div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
