<?php

namespace App\Services\Integrations;

use App\Models\Integration;

class ShippingIntegrationService
{
    public function quoteExternalShipping(Integration $integration, $cartOrOrder, array $destination): array { return ['success' => true, 'price' => 0, 'mock' => true]; }
    public function createExternalShipment(Integration $integration, $order): array { return ['success' => true, 'external_shipment_id' => 'SHIP-'.$order->id]; }
    public function generateExternalLabel(Integration $integration, $orderShipment): array { return ['success' => true, 'label' => 'mock']; }
    public function syncTracking(Integration $integration, $orderShipment): array { return ['success' => true, 'status' => 'mock']; }
    public function handleTrackingWebhook(Integration $integration, array $payload): array { return ['success' => true, 'payload' => $payload]; }
}
