<?php

namespace App\Services\Integrations;

use App\Models\ExternalProductMapping;
use App\Models\Integration;
use App\Services\PricingService;

class PriceSyncService
{
    public function __construct(private PricingService $pricingService, private IntegrationService $integrationService) {}

    public function syncProductPrice(Integration $integration, ExternalProductMapping $mapping): array { return $this->syncVariantPrice($integration, $mapping); }

    public function syncVariantPrice(Integration $integration, ExternalProductMapping $mapping): array
    {
        $payload = $this->preparePricePayload($mapping);
        $this->integrationService->logSuccess($integration, ['event_type' => 'price_sync', 'request_payload' => $payload, 'response_payload' => ['mock' => true]]);
        $this->markPriceSynced($mapping);
        return $payload;
    }

    public function syncAllPrices(Integration $integration): int
    {
        $count = 0;
        foreach ($integration->productMappings()->where('sync_price', true)->with(['product', 'variant'])->get() as $mapping) {
            $this->syncVariantPrice($integration, $mapping);
            $count++;
        }
        $integration->update(['last_sync_at' => now()]);
        return $count;
    }

    public function calculateExternalPrice($product, $variant = null): float
    {
        $result = $this->pricingService->getBestPrice($product, $variant);
        return max(0, (float) $result['final_price']);
    }

    public function preparePricePayload(ExternalProductMapping $mapping): array
    {
        $price = $this->calculateExternalPrice($mapping->product, $mapping->variant);
        $settings = $mapping->integration->settings ?? [];
        $price += (float) ($settings['price_markup_fixed'] ?? 0);
        $price *= 1 + ((float) ($settings['price_markup_percentage'] ?? 0) / 100);
        if ($settings['round_prices'] ?? false) $price = round($price);
        return ['external_product_id' => $mapping->external_product_id, 'external_variant_id' => $mapping->external_variant_id, 'price' => max(0, round($price, 2))];
    }

    public function markPriceSynced(ExternalProductMapping $mapping): void
    {
        $mapping->update(['last_price_sync_at' => now(), 'last_full_sync_at' => now()]);
    }
}
