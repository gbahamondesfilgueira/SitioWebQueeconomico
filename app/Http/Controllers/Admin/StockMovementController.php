<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    public function index(Request $request): View
    {
        $movements = StockMovement::query()->with(['warehouse', 'location', 'product', 'variant', 'user'])
            ->latest('created_at')->paginate(30)->withQueryString();
        return view('admin.stock_movements.index', compact('movements'));
    }
}
