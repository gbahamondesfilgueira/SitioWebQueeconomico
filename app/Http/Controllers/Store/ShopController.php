<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Services\ProductDisplayService;
use App\Services\StorefrontService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function __invoke(Request $request, StorefrontService $storefront, ProductDisplayService $display): View
    {
        $products = $storefront->productsForListing($request);

        return view('store.shop', [
            'products' => $products,
            'presentedProducts' => $products->getCollection()->map(fn ($product) => $display->presentProduct($product)),
            ...$storefront->filters(),
            'seo' => [
                'title' => 'Tienda',
                'description' => 'Catálogo público de productos.',
                'canonical' => route('store.shop'),
            ],
        ]);
    }
}
