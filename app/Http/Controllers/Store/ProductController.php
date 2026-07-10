<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductDisplayService;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(string $slug, ProductDisplayService $display): View
    {
        $product = Product::query()
            ->with(['brand', 'category.parent', 'originCountry', 'images', 'tags', 'relatedProducts.images', 'variants.attributeValues.attribute', 'weightUnit', 'dimensionUnit'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->where('is_visible', true)
            ->firstOrFail();

        $presented = $display->presentProduct($product);

        return view('store.products.show', [
            'product' => $product,
            'display' => $presented,
            'related' => $product->relatedProducts->map(fn ($related) => $display->presentProduct($related)),
            'seo' => [
                'title' => $product->seo_title ?: $product->name,
                'description' => $product->seo_description ?: $product->short_description,
                'canonical' => route('store.products.show', $product->slug),
                'image' => $presented['image'],
                'schemaProduct' => $presented,
            ],
        ]);
    }
}
