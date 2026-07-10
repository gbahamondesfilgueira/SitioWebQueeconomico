<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ApiResponses;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    use ApiResponses;
    public function index() { return $this->ok(Order::query()->with(['items', 'payments'])->latest()->paginate(25)); }
    public function show(Order $order) { return $this->ok($order->load(['items', 'payments', 'addresses', 'shipment'])); }
    public function store(Request $request) { return $this->fail(['order' => ['Creación directa preparada para fase de integración productiva.']], 'Endpoint preparado', 202); }
}
