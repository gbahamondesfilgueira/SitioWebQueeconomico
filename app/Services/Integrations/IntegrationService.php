<?php

namespace App\Services\Integrations;

use App\Contracts\IntegrationConnectorInterface;
use App\Jobs\ProcessWebhookEventJob;
use App\Models\Integration;
use App\Models\IntegrationLog;
use App\Models\SyncJob;
use App\Models\WebhookEvent;
use App\Services\AuditLogger;
use App\Services\Integrations\Connectors\BaseConnector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IntegrationService
{
    public function testConnection(Integration $integration): array
    {
        $log = $this->logRequest($integration, ['event_type' => 'test_connection', 'direction' => 'outbound']);
        $result = $this->connector($integration)->testConnection();
        $result['success'] ? $this->logSuccess($integration, ['log' => $log, 'response_payload' => $result]) : $this->logFailure($integration, ['log' => $log, 'error_message' => $result['message'] ?? 'Error']);
        AuditLogger::record('tested', 'integrations', "Conexión probada {$integration->code}");
        return $result;
    }

    public function dispatchSyncJob(Integration $integration, string $jobType, array $payload = []): SyncJob
    {
        if (! $integration->is_active) {
            throw new \InvalidArgumentException('No se puede sincronizar una integración inactiva.');
        }
        if ($jobType === 'full_sync' && $integration->syncJobs()->where('job_type', 'full_sync')->whereIn('status', ['pending', 'processing'])->exists()) {
            throw new \InvalidArgumentException('Ya existe una sincronización completa pendiente o en proceso.');
        }

        $job = $integration->syncJobs()->create([
            'job_type' => $jobType,
            'payload' => $payload,
            'created_by' => Auth::id(),
        ]);
        AuditLogger::record('created', 'sync_jobs', "Job {$jobType} creado para {$integration->code}");
        return $job;
    }

    public function logRequest(?Integration $integration, array $data): IntegrationLog
    {
        return IntegrationLog::query()->create([
            'integration_id' => $integration?->id,
            'direction' => $data['direction'] ?? 'outbound',
            'event_type' => $data['event_type'] ?? 'request',
            'status' => 'pending',
            'method' => $data['method'] ?? null,
            'endpoint' => $data['endpoint'] ?? null,
            'request_payload' => $this->sanitizePayload($data['request_payload'] ?? $data['payload'] ?? []),
            'reference_type' => $data['reference_type'] ?? null,
            'reference_id' => $data['reference_id'] ?? null,
            'external_reference' => $data['external_reference'] ?? null,
            'started_at' => now(),
            'created_at' => now(),
        ]);
    }

    public function logSuccess(?Integration $integration, array $data): IntegrationLog
    {
        $log = $data['log'] ?? null;
        if (! $log instanceof IntegrationLog) $log = $this->logRequest($integration, $data);
        $log->update([
            'status' => 'success',
            'response_payload' => $this->sanitizePayload($data['response_payload'] ?? []),
            'http_status' => $data['http_status'] ?? 200,
            'finished_at' => now(),
        ]);
        return $log;
    }

    public function logFailure(?Integration $integration, array $data): IntegrationLog
    {
        $log = $data['log'] ?? null;
        if (! $log instanceof IntegrationLog) $log = $this->logRequest($integration, $data);
        $log->update([
            'status' => 'failed',
            'response_payload' => $this->sanitizePayload($data['response_payload'] ?? []),
            'error_message' => $data['error_message'] ?? 'Error de integración',
            'http_status' => $data['http_status'] ?? null,
            'finished_at' => now(),
            'attempts' => $log->attempts + 1,
        ]);
        AuditLogger::record('critical_error', 'integration_logs', $log->error_message);
        return $log;
    }

    public function maskCredentials(Integration $integration): array
    {
        return [
            'api_key' => $this->mask($integration->api_key),
            'api_secret' => $this->mask($integration->api_secret),
            'access_token' => $this->mask($integration->access_token),
            'refresh_token' => $this->mask($integration->refresh_token),
            'webhook_secret' => $this->mask($integration->webhook_secret),
        ];
    }

    public function refreshTokenIfNeeded(Integration $integration): bool
    {
        return ! $integration->token_expires_at || $integration->token_expires_at->isFuture();
    }

    public function handleWebhook(string $provider, array $payload, array $headers = []): WebhookEvent
    {
        return DB::transaction(function () use ($provider, $payload, $headers) {
            $integration = Integration::query()->where('code', $provider)->orWhere('provider_name', $provider)->first();
            $externalId = $payload['id'] ?? $payload['event_id'] ?? $payload['resource_id'] ?? null;
            $event = WebhookEvent::query()->firstOrCreate([
                'event_source' => $provider,
                'external_event_id' => $externalId,
            ], [
                'integration_id' => $integration?->id,
                'event_type' => $payload['type'] ?? $payload['event'] ?? 'generic',
                'payload' => $this->sanitizePayload($payload),
                'headers' => $this->sanitizePayload($headers),
                'signature' => $headers['x-signature'] ?? $headers['X-Signature'] ?? null,
                'status' => 'received',
                'created_at' => now(),
            ]);
            AuditLogger::record('received', 'webhook_events', "Webhook recibido {$provider}");
            ProcessWebhookEventJob::dispatch($event);
            return $event;
        });
    }

    public function validateWebhookSignature(?Integration $integration, array $payload, ?string $signature): bool
    {
        if (! $integration?->webhook_secret) return true;
        return hash_equals(hash_hmac('sha256', json_encode($payload), $integration->webhook_secret), (string) $signature);
    }

    public function retryFailedLog(IntegrationLog $integrationLog): IntegrationLog
    {
        $integrationLog->update(['status' => 'retrying', 'attempts' => $integrationLog->attempts + 1]);
        return $integrationLog;
    }

    public function updateIntegrationStatus(Integration $integration, string $status): Integration
    {
        $integration->update(['status' => $status, 'is_active' => $status === 'active']);
        AuditLogger::record('status_updated', 'integrations', "Integración {$integration->code}: {$status}");
        return $integration;
    }

    public function connector(Integration $integration): IntegrationConnectorInterface
    {
        $map = [
            'woocommerce' => Connectors\WooCommerceConnector::class,
            'mercado_libre' => Connectors\MercadoLibreConnector::class,
            'falabella' => Connectors\FalabellaConnector::class,
            'shopify' => Connectors\ShopifyConnector::class,
            'webpay' => Connectors\WebpayConnector::class,
            'flow' => Connectors\FlowConnector::class,
            'mercado_pago' => Connectors\MercadoPagoConnector::class,
            'blue_express' => Connectors\BlueExpressConnector::class,
            'chilexpress' => Connectors\ChilexpressConnector::class,
            'starken' => Connectors\StarkenConnector::class,
            'shipit' => Connectors\ShipitConnector::class,
            'enviame' => Connectors\EnviameConnector::class,
        ];
        $class = $map[$integration->code] ?? BaseConnector::class;
        return new $class($integration);
    }

    public function sanitizePayload(array $payload): array
    {
        foreach ($payload as $key => $value) {
            if (str_contains(strtolower((string) $key), 'token') || str_contains(strtolower((string) $key), 'secret') || str_contains(strtolower((string) $key), 'key')) {
                $payload[$key] = '***';
            } elseif (is_array($value)) {
                $payload[$key] = $this->sanitizePayload($value);
            }
        }
        return $payload;
    }

    private function mask(?string $value): ?string
    {
        if (! $value) return null;
        return substr($value, 0, 4).'...'.substr($value, -4);
    }
}
