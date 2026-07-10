<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingCarrier;
use App\Models\ShippingIntegration;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShippingIntegrationController extends Controller
{
    public function index(): View { return view('admin.shipping.integrations.index', ['integrations' => ShippingIntegration::query()->with('carrier')->paginate(15), 'carriers' => ShippingCarrier::query()->orderBy('name')->get()]); }
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['shipping_carrier_id' => ['required', 'exists:shipping_carriers,id'], 'integration_type' => ['required', 'in:manual,api'], 'api_base_url' => ['nullable', 'url'], 'api_key' => ['nullable'], 'api_secret' => ['nullable'], 'account_number' => ['nullable'], 'sandbox_mode' => ['nullable', 'boolean'], 'is_active' => ['nullable', 'boolean']]);
        ShippingIntegration::query()->updateOrCreate(['shipping_carrier_id' => $data['shipping_carrier_id']], $data + ['sandbox_mode' => $request->boolean('sandbox_mode', true), 'is_active' => $request->boolean('is_active', true)]);
        AuditLogger::record('configured', 'shipping_integrations', 'Integración envío configurada');
        return back()->with('success', 'Integración guardada.');
    }
}
