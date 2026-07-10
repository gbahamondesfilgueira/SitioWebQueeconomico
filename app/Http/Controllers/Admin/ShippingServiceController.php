<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesShippingResources;
use App\Http\Controllers\Controller;
use App\Models\ShippingCarrier;
use App\Models\ShippingService as ShippingServiceModel;
use Illuminate\Validation\Rule;

class ShippingServiceController extends Controller
{
    use ManagesShippingResources;
    protected string $modelClass = ShippingServiceModel::class;
    protected string $title = 'Servicios de envío';
    protected string $route = 'services';
    protected string $module = 'shipping_services';
    protected array $with = ['carrier'];
    protected array $columns = ['carrier.name' => 'Transportista', 'name' => 'Nombre', 'code' => 'Código', 'service_type' => 'Tipo', 'is_active' => 'Estado'];
    protected array $fields = ['shipping_carrier_id' => 'select:carriers', 'name' => 'text', 'code' => 'text', 'service_type' => 'select:serviceTypes', 'description' => 'textarea', 'estimated_days_min' => 'number', 'estimated_days_max' => 'number', 'is_active' => 'checkbox'];
    protected function rules($item = null): array { return ['shipping_carrier_id' => ['required', 'exists:shipping_carriers,id'], 'name' => ['required'], 'code' => ['required'], 'service_type' => ['required', Rule::in(['delivery', 'pickup', 'same_day', 'express', 'standard'])], 'description' => ['nullable'], 'estimated_days_min' => ['nullable', 'integer', 'min:0'], 'estimated_days_max' => ['nullable', 'integer', 'min:0'], 'is_active' => ['nullable', 'boolean']]; }
    protected function extraFormData(): array { return ['carriers' => ShippingCarrier::query()->where('is_active', true)->orderBy('name')->get(), 'serviceTypes' => collect(['delivery' => 'Delivery', 'pickup' => 'Retiro', 'same_day' => 'Mismo día', 'express' => 'Express', 'standard' => 'Estándar'])]; }
}
