<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ApiResponses;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockLevel;

class StockApiController extends Controller
{
    use ApiResponses;
    public function index() { return $this->ok(StockLevel::query()->with(['product', 'variant', 'warehouse'])->paginate(50)); }
    public function show(Product $product) { return $this->ok(StockLevel::query()->with(['variant', 'warehouse'])->where('product_id', $product->id)->get()); }
}
