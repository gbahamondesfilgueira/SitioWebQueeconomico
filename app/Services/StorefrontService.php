<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\OriginCountry;
use App\Models\Product;
use App\Models\ProductPack;
use App\Models\StockLevel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StorefrontService
{
    public function visibleProducts(): Builder
    {
        return Product::query()
            ->with(['brand', 'category', 'images', 'tags', 'originCountry', 'variants.attributeValues.attribute'])
            ->where('is_active', true)
            ->where('is_visible', true);
    }

    public function productsForListing(Request $request, ?Category $category = null): LengthAwarePaginator
    {
        $query = $this->visibleProducts();

        if ($category) {
            $categoryIds = Category::query()->where('parent_id', $category->id)->pluck('id')->push($category->id);
            $query->whereIn('category_id', $categoryIds);
        }

        $query
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->integer('category_id')))
            ->when($request->filled('brand_id'), fn ($q) => $q->where('brand_id', $request->integer('brand_id')))
            ->when($request->filled('origin_country_id'), fn ($q) => $q->where('origin_country_id', $request->integer('origin_country_id')))
            ->when($request->boolean('featured'), fn ($q) => $q->where('is_featured', true))
            ->when($request->boolean('on_sale'), fn ($q) => $q->whereNotNull('sale_price')->where(fn ($date) => $date->whereNull('sale_ends_at')->orWhere('sale_ends_at', '>=', now())))
            ->when($request->filled('min_price'), fn ($q) => $q->where('regular_price', '>=', $request->input('min_price')))
            ->when($request->filled('max_price'), fn ($q) => $q->where('regular_price', '<=', $request->input('max_price')))
            ->when($request->boolean('with_stock'), fn ($q) => $q->whereIn('id', StockLevel::query()->select('product_id')->get()->filter(fn ($stock) => $stock->available_stock > 0)->pluck('product_id')));

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhereHas('brand', fn ($b) => $b->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('tags', fn ($t) => $t->where('name', 'like', "%{$search}%"));
            });
        }

        match ($request->input('sort', 'recent')) {
            'price_asc' => $query->orderBy('regular_price'),
            'price_desc' => $query->orderByDesc('regular_price'),
            'name_asc' => $query->orderBy('name'),
            'name_desc' => $query->orderByDesc('name'),
            'featured' => $query->orderByDesc('is_featured')->latest(),
            default => $query->latest(),
        };

        return $query->paginate(12)->withQueryString();
    }

    public function filters(): array
    {
        return [
            'categories' => Category::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
            'brands' => Brand::query()->where('is_active', true)->orderBy('name')->get(),
            'originCountries' => OriginCountry::query()->where('is_active', true)->orderBy('name')->get(),
        ];
    }

    public function categoryMenu()
    {
        return Category::query()
            ->with(['children' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('name')])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function visiblePacks()
    {
        return ProductPack::query()
            ->with('items.product.images', 'items.variant')
            ->where('is_active', true)
            ->where('is_visible', true)
            ->get()
            ->filter(fn ($pack) => $pack->isCurrentlyActive())
            ->values();
    }
}
