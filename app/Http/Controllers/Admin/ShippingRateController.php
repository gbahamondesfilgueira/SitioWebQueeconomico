<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesShippingResources;
use App\Http\Controllers\Controller;
use App\Models\ShippingCarrier;
use App\Models\ShippingRate;
use App\Models\ShippingService as ShippingServiceModel;
use App\Models\ShippingZone;

class ShippingRateController extends Controller
{
    use ManagesShippingResources;
    protected string $modelClass = ShippingRate::class;
    protected string $title = 'Tarifas de envío';
    protected string $route = 'rates';
    protected string $module = 'shipping_rates';
    protected array $with = ['carrier', 'service', 'zone'];
    protected array $columns = ['carrier.name' => 'Transportista', 'service.name' => 'Servicio', 'zone.name' => 'Zona', 'max_weight' => 'Peso máx.', 'price' => 'Precio', 'is_active' => 'Estado'];
    protected array $fields = ['shipping_carrier_id' => 'select:carriers', 'shipping_service_id' => 'select:services', 'shipping_zone_id' => 'select:zones', 'min_weight' => 'number', 'max_weight' => 'number', 'max_height' => 'number', 'max_width' => 'number', 'max_length' => 'number', 'max_volume' => 'number', 'price' => 'number', 'currency' => 'text', 'starts_at' => 'datetime-local', 'ends_at' => 'datetime-local', 'is_active' => 'checkbox'];
    protected function rules($item = null): array { return ['shipping_carrier_id' => ['required', 'exists:shipping_carriers,id'], 'shipping_service_id' => ['nullable', 'exists:shipping_services,id'], 'shipping_zone_id' => ['required', 'exists:shipping_zones,id'], 'min_weight' => ['required', 'numeric', 'min:0'], 'max_weight' => ['nullable', 'numeric', 'gt:min_weight'], 'max_height' => ['nullable', 'numeric', 'min:0'], 'max_width' => ['nullable', 'numeric', 'min:0'], 'max_length' => ['nullable', 'numeric', 'min:0'], 'max_volume' => ['nullable', 'numeric', 'min:0'], 'price' => ['required', 'numeric', 'min:0'], 'currency' => ['required'], 'starts_at' => ['nullable', 'date'], 'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'], 'is_active' => ['nullable', 'boolean']]; }
    protected function extraFormData(): array { return ['carriers' => ShippingCarrier::query()->orderBy('name')->get(), 'services' => ShippingServiceModel::query()->orderBy('name')->get(), 'zones' => ShippingZone::query()->orderBy('name')->get()]; }
}
