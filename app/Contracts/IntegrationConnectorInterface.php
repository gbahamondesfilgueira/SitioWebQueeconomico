<?php

namespace App\Contracts;

use App\Models\Order;

interface IntegrationConnectorInterface
{
    public function testConnection(): array;
    public function syncProducts(): array;
    public function syncStock(): array;
    public function syncPrices(): array;
    public function importOrders(): array;
    public function exportOrder(Order $order): array;
    public function syncCustomers(): array;
    public function handleWebhook(array $payload, array $headers = []): array;
    public function getLastError(): ?string;
}
