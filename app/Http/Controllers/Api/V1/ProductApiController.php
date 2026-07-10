<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ApiResponses;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductApiController extends Controller
{
    use ApiResponses;
    public function index() { return $this->ok(Product::query()->with(['brand', 'category'])->where('is_active', true)->paginate(25)); }
    public function show(Product $product) { return $this->ok($product->load(['brand', 'category', 'variants', 'images'])); }
}
