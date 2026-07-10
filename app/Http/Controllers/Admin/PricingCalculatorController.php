<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PriceList;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PricingCalculatorController extends Controller
{
    public function __invoke(Request $request, PricingService $pricing): View
    {
        $result = null;
        if ($request->filled('product_id')) {
            $product = Product::query()->findOrFail($request->integer('product_id'));
            $variant = $request->filled('product_variant_id') ? ProductVariant::query()->find($request->integer('product_variant_id')) : null;
            $user = $request->filled('user_id') ? User::query()->find($request->integer('user_id')) : null;
            $priceList = $request->filled('price_list_id') ? PriceList::query()->find($request->integer('price_list_id')) : null;
            $result = $pricing->getBestPrice($product, $variant, max(1, $request->integer('quantity', 1)), $user, $priceList, $request->input('coupon_code'));
        }

        return view('admin.pricing.calculator', [
            'products' => Product::query()->orderBy('name')->get(),
            'variants' => ProductVariant::query()->with('product')->get(),
            'users' => User::query()->orderBy('name')->get(),
            'priceLists' => PriceList::query()->orderBy('name')->get(),
            'result' => $result,
        ]);
    }
}
