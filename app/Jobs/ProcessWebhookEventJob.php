<?php

namespace App\Jobs;

use App\Models\WebhookEvent;
use App\Services\Integrations\IntegrationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessWebhookEventJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public WebhookEvent $webhookEvent) {}

    public function handle(IntegrationService $integrationService): void
    {
        $this->webhookEvent->update(['status' => 'processing']);
        $integration = $this->webhookEvent->integration;
        if ($integration && ! $integrationService->validateWebhookSignature($integration, $this->webhookEvent->payload, $this->webhookEvent->signature)) {
            $this->webhookEvent->update(['status' => 'failed', 'error_message' => 'Firma inválida', 'processed_at' => now()]);
            return;
        }
        $integration ? $integrationService->connector($integration)->handleWebhook($this->webhookEvent->payload, $this->webhookEvent->headers ?? []) : null;
        $this->webhookEvent->update(['status' => 'processed', 'processed_at' => now()]);
    }
}
