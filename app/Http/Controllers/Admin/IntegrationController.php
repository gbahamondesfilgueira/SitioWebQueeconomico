<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Integration;
use App\Services\AuditLogger;
use App\Services\Integrations\IntegrationService;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    public function index()
    {
        return view('admin.integrations.index', [
            'integrations' => Integration::query()->withCount(['logs', 'productMappings', 'syncJobs'])->latest()->paginate(20),
            'activeCount' => Integration::query()->where('is_active', true)->count(),
            'errorCount' => Integration::query()->where('status', 'error')->count(),
        ]);
    }

    public function create() { return view('admin.integrations.create', ['integration' => new Integration()]); }

    public function store(Request $request)
    {
        $integration = Integration::query()->create($this->validated($request));
        AuditLogger::record('created', 'integrations', "Integración creada {$integration->code}");
        return redirect()->route('admin.integrations.show', $integration)->with('success', 'Integración creada.');
    }

    public function show(Integration $integration, IntegrationService $service)
    {
        return view('admin.integrations.show', [
            'integration' => $integration->loadCount(['logs', 'productMappings', 'orderMappings', 'webhookEvents', 'syncJobs']),
            'masked' => $service->maskCredentials($integration),
            'recentLogs' => $integration->logs()->latest('created_at')->limit(10)->get(),
        ]);
    }

    public function edit(Integration $integration) { return view('admin.integrations.edit', compact('integration')); }

    public function update(Request $request, Integration $integration)
    {
        $data = $this->validated($request, $integration->id);
        foreach (['api_key', 'api_secret', 'access_token', 'refresh_token', 'webhook_secret'] as $field) {
            if (blank($data[$field] ?? null)) unset($data[$field]);
        }
        $integration->update($data);
        AuditLogger::record('updated', 'integrations', "Integración editada {$integration->code}");
        return redirect()->route('admin.integrations.show', $integration)->with('success', 'Integración actualizada.');
    }

    public function test(Integration $integration, IntegrationService $service)
    {
        $result = $service->testConnection($integration);
        return back()->with($result['success'] ? 'success' : 'error', $result['message'] ?? 'Prueba ejecutada.');
    }

    public function syncStock(Integration $integration, IntegrationService $service)
    {
        $service->dispatchSyncJob($integration, 'stock_sync');
        return back()->with('success', 'Job de stock creado.');
    }

    public function syncPrices(Integration $integration, IntegrationService $service)
    {
        $service->dispatchSyncJob($integration, 'price_sync');
        return back()->with('success', 'Job de precios creado.');
    }

    public function importOrders(Integration $integration, IntegrationService $service)
    {
        $service->dispatchSyncJob($integration, 'order_import');
        return back()->with('success', 'Job de importación creado.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'code' => ['required', 'string', 'max:80', 'unique:integrations,code'.($id ? ','.$id : '')],
            'provider_type' => ['required', 'in:ecommerce,marketplace,payment,shipping,accounting,erp,custom_api'],
            'provider_name' => ['required', 'string', 'max:190'],
            'description' => ['nullable', 'string'],
            'environment' => ['required', 'in:sandbox,production'],
            'status' => ['required', 'in:inactive,active,error,suspended'],
            'base_url' => ['nullable', 'url'],
            'api_key' => ['nullable', 'string'],
            'api_secret' => ['nullable', 'string'],
            'access_token' => ['nullable', 'string'],
            'refresh_token' => ['nullable', 'string'],
            'webhook_secret' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active'), 'settings' => []];
    }
}
