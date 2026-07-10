<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesShippingResources;
use App\Http\Controllers\Controller;
use App\Models\ShippingCarrier;
use Illuminate\Validation\Rule;

class ShippingCarrierController extends Controller
{
    use ManagesShippingResources;
    protected string $modelClass = ShippingCarrier::class;
    protected string $title = 'Transportistas';
    protected string $route = 'carriers';
    protected string $module = 'shipping_carriers';
    protected array $columns = ['name' => 'Nombre', 'code' => 'Código', 'contact_email' => 'Email', 'is_active' => 'Estado'];
    protected array $fields = ['name' => 'text', 'code' => 'text', 'website' => 'url', 'contact_email' => 'email', 'contact_phone' => 'text', 'tracking_url_template' => 'text', 'is_active' => 'checkbox', 'supports_api' => 'checkbox'];
    protected function rules($item = null): array { return ['name' => ['required'], 'code' => ['required', Rule::unique('shipping_carriers', 'code')->ignore($item)], 'website' => ['nullable', 'url'], 'contact_email' => ['nullable', 'email'], 'contact_phone' => ['nullable'], 'tracking_url_template' => ['nullable'], 'is_active' => ['nullable', 'boolean'], 'supports_api' => ['nullable', 'boolean']]; }
}
