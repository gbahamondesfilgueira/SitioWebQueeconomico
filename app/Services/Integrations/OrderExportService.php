<?php

namespace App\Services\Integrations;

use App\Models\Integration;
use App\Models\Order;

class OrderExportService
{
    public function exportOrder(Integration $integration, Order $order): array
    {
        $payload = $this->prepareOrderPayload($integration, $order);
        $this->markOrderExported($integration, $order, 'MOCK-'.$order->order_number);
        return $payload;
    }

    public function prepareOrderPayload(Integration $integration, Order $order): array
    {
        return ['order_number' => $order->order_number, 'total' => $order->grand_total, 'items' => $order->items()->count()];
    }

    public function markOrderExported(Integration $integration, Order $order, string $externalReference): void
    {
        $integration->orderMappings()->updateOrCreate(['order_id' => $order->id, 'external_order_id' => $externalReference], ['external_order_number' => $externalReference, 'sync_status' => 'synced', 'last_sync_at' => now()]);
    }
}
