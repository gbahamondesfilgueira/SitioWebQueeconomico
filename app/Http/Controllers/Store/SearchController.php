<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Services\AuditLogger;
use App\Services\ProductDisplayService;
use App\Services\StorefrontService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __invoke(Request $request, StorefrontService $storefront, ProductDisplayService $display): View
    {
        $products = $storefront->productsForListing($request);

        if ($request->filled('q')) {
            AuditLogger::record('searched', 'store_search', 'Búsqueda pública: '.$request->string('q')->toString());
        }

        return view('store.search', [
            'query' => $request->string('q')->toString(),
            'products' => $products,
            'presentedProducts' => $products->getCollection()->map(fn ($product) => $display->presentProduct($product)),
            ...$storefront->filters(),
            'seo' => [
                'title' => 'Buscar productos',
                'description' => 'Resultados de búsqueda en Qué Económico.',
                'canonical' => route('store.search'),
            ],
        ]);
    }
}
