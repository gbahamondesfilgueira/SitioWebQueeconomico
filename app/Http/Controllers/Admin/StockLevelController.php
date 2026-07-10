<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockLevel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockLevelController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $levels = StockLevel::query()->with(['warehouse', 'location', 'product', 'variant'])
            ->when($search, fn ($q) => $q->whereHas('product', fn ($p) => $p->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%")))
            ->latest()->paginate(20)->withQueryString();
        return view('admin.stock.index', compact('levels', 'search'));
    }

    public function show(StockLevel $stock): View
    {
        return view('admin.stock.show', ['level' => $stock->load(['warehouse', 'location', 'product', 'variant'])]);
    }
}
