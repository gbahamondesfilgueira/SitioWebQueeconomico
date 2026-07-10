<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    public function rememberSettings(): Setting
    {
        return Cache::remember('store.settings', now()->addMinutes(30), fn () => Setting::current());
    }

    public function rememberActiveCategories()
    {
        return Cache::remember('store.categories.active', now()->addMinutes(30), fn () => Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get());
    }

    public function rememberActiveBrands()
    {
        return Cache::remember('store.brands.active', now()->addMinutes(30), fn () => Brand::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get());
    }

    public function rememberFeaturedProducts()
    {
        return Cache::remember('store.products.featured', now()->addMinutes(10), fn () => Product::query()
            ->with(['primaryImage', 'brand', 'category'])
            ->where('is_active', true)
            ->where('is_visible', true)
            ->where('is_featured', true)
            ->latest()
            ->limit(12)
            ->get());
    }

    public function rememberHomeSections(): array
    {
        return Cache::remember('store.home.sections', now()->addMinutes(10), fn () => [
            'categories' => $this->rememberActiveCategories()->take(8),
            'brands' => $this->rememberActiveBrands()->take(8),
            'featured_products' => $this->rememberFeaturedProducts(),
        ]);
    }

    public function rememberDashboard(string $key, callable $callback)
    {
        return Cache::remember('admin.dashboard.'.$key, now()->addMinutes(5), $callback);
    }

    public function clearStoreCache(): void
    {
        Cache::forget('store.settings');
        Cache::forget('store.categories.active');
        Cache::forget('store.brands.active');
        Cache::forget('store.products.featured');
        Cache::forget('store.home.sections');
    }

    public function clearAdminCache(): void
    {
        Cache::forget('admin.dashboard.executive');
    }

    public function clearProductCache(int $productId): void
    {
        Cache::forget('store.product.'.$productId);
        Cache::forget('store.products.featured');
        Cache::forget('store.home.sections');
    }

    public function clearAllCache(): void
    {
        Cache::flush();
    }
}
