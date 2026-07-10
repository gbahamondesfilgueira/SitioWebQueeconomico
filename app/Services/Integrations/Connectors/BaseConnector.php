<?php

namespace App\Services\Integrations\Connectors;

use App\Contracts\IntegrationConnectorInterface;
use App\Models\Integration;
use App\Models\Order;

abstract class BaseConnector implements IntegrationConnectorInterface
{
    protected ?string $lastError = null;

    public function __construct(protected Integration $integration) {}

    public function testConnection(): array
    {
        if (! $this->integration->is_active || $this->integration->status !== 'active') {
            $this->lastError = 'La integración está inactiva.';
            return ['success' => false, 'message' => $this->lastError];
        }

        return ['success' => true, 'message' => 'Conexión mock/sandbox correcta.'];
    }

    public function syncProducts(): array { return $this->mock('product_sync'); }
    public function syncStock(): array { return $this->mock('stock_sync'); }
    public function syncPrices(): array { return $this->mock('price_sync'); }
    public function importOrders(): array { return $this->mock('order_import'); }
    public function exportOrder(Order $order): array { return ['success' => true, 'external_reference' => 'MOCK-'.$order->order_number]; }
    public function syncCustomers(): array { return $this->mock('customer_sync'); }
    public function handleWebhook(array $payload, array $headers = []): array { return ['success' => true, 'message' => 'Webhook aceptado en modo mock.']; }
    public function getLastError(): ?string { return $this->lastError; }

    protected function mock(string $type): array
    {
        return ['success' => true, 'type' => $type, 'message' => 'Operación preparada en modo mock/sandbox.'];
    }
}
