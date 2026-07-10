<?php

namespace App\Services\Integrations;

use App\Models\ExternalProductMapping;
use App\Models\Integration;
use App\Services\InventoryService;

class StockSyncService
{
    public function __construct(private InventoryService $inventoryService, private IntegrationService $integrationService) {}

    public function syncProductStock(Integration $integration, ExternalProductMapping $mapping): array { return $this->syncVariantStock($integration, $mapping); }

    public function syncVariantStock(Integration $integration, ExternalProductMapping $mapping): array
    {
        $payload = $this->prepareStockPayload($mapping);
        $this->integrationService->logSuccess($integration, ['event_type' => 'stock_sync', 'request_payload' => $payload, 'response_payload' => ['mock' => true]]);
        $this->markStockSynced($mapping);
        return $payload;
    }

    public function syncAllStock(Integration $integration): int
    {
        $count = 0;
        foreach ($integration->productMappings()->where('sync_stock', true)->with(['product', 'variant'])->get() as $mapping) {
            $this->syncVariantStock($integration, $mapping);
            $count++;
        }
        $integration->update(['last_sync_at' => now()]);
        return $count;
    }

    public function calculateExternalAvailableStock($product, $variant = null): float
    {
        $warehouseId = $product->integration_stock_warehouse_id ?? null;
        if (! $warehouseId) return 0;
        return max(0, $this->inventoryService->getAvailableStock((int) $warehouseId, $product->id, $variant?->id));
    }

    public function prepareStockPayload(ExternalProductMapping $mapping): array
    {
        $warehouseId = (int) ($mapping->integration->settings['stock_warehouse_id'] ?? 0);
        $stock = $warehouseId ? $this->inventoryService->getAvailableStock($warehouseId, $mapping->product_id, $mapping->product_variant_id) : 0;
        return ['external_product_id' => $mapping->external_product_id, 'external_variant_id' => $mapping->external_variant_id, 'stock' => max(0, $stock)];
    }

    public function markStockSynced(ExternalProductMapping $mapping): void
    {
        $mapping->update(['last_stock_sync_at' => now(), 'last_full_sync_at' => now()]);
    }
}
