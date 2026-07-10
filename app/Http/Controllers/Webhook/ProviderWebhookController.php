<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Services\Integrations\IntegrationService;
use Illuminate\Http\Request;

class ProviderWebhookController extends Controller
{
    public function __invoke(string $provider, ?string $event = null, Request $request, IntegrationService $service)
    {
        $payload = $request->all();
        if ($event) $payload['event'] = $event;
        $webhook = $service->handleWebhook($provider, $payload, $request->headers->all());
        return response()->json(['success' => true, 'event_id' => $webhook->id]);
    }
}
