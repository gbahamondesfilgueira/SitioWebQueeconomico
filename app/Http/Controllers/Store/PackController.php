<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\ProductPack;
use App\Services\ProductDisplayService;
use App\Services\StorefrontService;
use Illuminate\View\View;

class PackController extends Controller
{
    public function index(StorefrontService $storefront, ProductDisplayService $display): View
    {
        return view('store.packs.index', [
            'packs' => $storefront->visiblePacks()->map(fn ($pack) => $display->presentPack($pack)),
            'seo' => [
                'title' => 'Packs',
                'description' => 'Packs y combos disponibles.',
                'canonical' => route('store.packs.index'),
            ],
        ]);
    }

    public function show(string $slug, ProductDisplayService $display): View
    {
        $pack = ProductPack::query()
            ->with('items.product.images', 'items.variant')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->where('is_visible', true)
            ->firstOrFail();

        abort_unless($pack->isCurrentlyActive(), 404);

        $presented = $display->presentPack($pack);

        return view('store.packs.show', [
            'pack' => $pack,
            'display' => $presented,
            'seo' => [
                'title' => $pack->name,
                'description' => $pack->description,
                'canonical' => route('store.packs.show', $pack->slug),
                'image' => $presented['image'],
            ],
        ]);
    }
}
