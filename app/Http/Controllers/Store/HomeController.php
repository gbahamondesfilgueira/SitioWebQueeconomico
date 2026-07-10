<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\NewsletterSubscriber;
use App\Services\AuditLogger;
use App\Services\ProductDisplayService;
use App\Services\StorefrontService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(StorefrontService $storefront, ProductDisplayService $display): View
    {
        $featured = $storefront->visibleProducts()->where('is_featured', true)->limit(8)->get();
        $latest = $storefront->visibleProducts()->latest()->limit(8)->get();
        $offers = $storefront->visibleProducts()->whereNotNull('sale_price')->limit(8)->get();

        return view('store.home', [
            'featuredCategories' => Category::query()->where('is_active', true)->whereNull('parent_id')->orderBy('sort_order')->limit(6)->get(),
            'featuredProducts' => $featured->map(fn ($product) => $display->presentProduct($product)),
            'offerProducts' => $offers->map(fn ($product) => $display->presentProduct($product)),
            'latestProducts' => $latest->map(fn ($product) => $display->presentProduct($product)),
            'packs' => $storefront->visiblePacks()->take(4)->map(fn ($pack) => $display->presentPack($pack)),
            'brands' => Brand::query()->where('is_active', true)->orderBy('name')->limit(8)->get(),
            'seo' => [
                'title' => 'Qué Económico | Tienda online',
                'description' => 'Compra productos, ofertas y packs en Qué Económico.',
                'canonical' => route('store.home'),
            ],
        ]);
    }

    public function newsletter(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:100'],
        ]);

        NewsletterSubscriber::query()->updateOrCreate(
            ['email' => strtolower($data['email'])],
            [
                'name' => $data['name'] ?? null,
                'source' => $data['source'] ?? 'storefront',
                'is_active' => true,
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ],
        );

        AuditLogger::record('subscribed', 'newsletter', "Alta newsletter: {$data['email']}");

        return back()->with('success', 'Te suscribiste correctamente al newsletter.');
    }
}
