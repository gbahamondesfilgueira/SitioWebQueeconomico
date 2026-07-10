<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ProductDisplayService;
use App\Services\StorefrontService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(Request $request, StorefrontService $storefront, ProductDisplayService $display, string $slug, ?string $child = null): View
    {
        $category = Category::query()
            ->with('children')
            ->where('slug', $child ?: $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = $storefront->productsForListing($request, $category);

        return view('store.category', [
            'category' => $category,
            'products' => $products,
            'presentedProducts' => $products->getCollection()->map(fn ($product) => $display->presentProduct($product)),
            ...$storefront->filters(),
            'seo' => [
                'title' => $category->name,
                'description' => $category->description,
                'canonical' => route('store.categories.show', $category->slug),
            ],
        ]);
    }
}
