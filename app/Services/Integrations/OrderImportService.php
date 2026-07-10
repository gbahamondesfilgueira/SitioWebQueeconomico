<?php

namespace App\Services\Integrations;

use App\Models\CustomerProfile;
use App\Models\ExternalOrderMapping;
use App\Models\ExternalProductMapping;
use App\Models\Integration;

class OrderImportService
{
    public function __construct(private IntegrationService $integrationService) {}

    public function importOrders(Integration $integration): int { return 0; }
    public function importOrderByExternalId(Integration $integration, string $externalOrderId): ?ExternalOrderMapping { return $integration->orderMappings()->where('external_order_id', $externalOrderId)->first(); }
    public function mapExternalOrderToInternal(Integration $integration, array $externalData): array { return $externalData; }
    public function findOrCreateCustomer(array $externalCustomerData): ?CustomerProfile { return CustomerProfile::query()->where('email', $externalCustomerData['email'] ?? null)->first(); }
    public function findMappedProduct(Integration $integration, string $externalProductId, ?string $externalVariantId = null): ?ExternalProductMapping { return $integration->productMappings()->where('external_product_id', $externalProductId)->when($externalVariantId, fn ($q) => $q->where('external_variant_id', $externalVariantId))->first(); }
    public function createInternalOrderFromExternal(Integration $integration, array $mappedData): array { return ['pending_review' => true, 'data' => $mappedData]; }
    public function markOrderImported(ExternalOrderMapping $mapping): void { $mapping->update(['import_status' => 'imported', 'last_sync_at' => now()]); }
}
