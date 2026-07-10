<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesShippingResources;
use App\Http\Controllers\Controller;
use App\Models\ShippingZone;

class ShippingZoneController extends Controller
{
    use ManagesShippingResources;
    protected string $modelClass = ShippingZone::class;
    protected string $title = 'Zonas de envío';
    protected string $route = 'zones';
    protected string $module = 'shipping_zones';
    protected array $columns = ['name' => 'Nombre', 'country' => 'País', 'region' => 'Región', 'commune' => 'Comuna', 'is_active' => 'Estado'];
    protected array $fields = ['name' => 'text', 'country' => 'text', 'region' => 'text', 'commune' => 'text', 'city' => 'text', 'postal_code' => 'text', 'is_active' => 'checkbox'];
    protected function rules($item = null): array { return ['name' => ['required'], 'country' => ['required'], 'region' => ['nullable'], 'commune' => ['nullable'], 'city' => ['nullable'], 'postal_code' => ['nullable'], 'is_active' => ['nullable', 'boolean']]; }
}
