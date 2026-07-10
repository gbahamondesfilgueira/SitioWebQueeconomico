<?php

namespace App\Services\Integrations;

use App\Models\Integration;
use App\Models\Order;

class PaymentIntegrationService
{
    public function createPaymentIntent(Integration $integration, Order $order): array { return ['success' => true, 'mock_intent_id' => 'PAY-'.$order->order_number]; }
    public function confirmPayment(Integration $integration, array $payload): array { return ['success' => true, 'payload' => $payload]; }
    public function handlePaymentWebhook(Integration $integration, array $payload): array { return ['success' => true, 'handled' => true]; }
    public function updateOrderPaymentStatus(Order $order, string $status, array $metadata = []): Order { $order->update(['payment_status' => $status]); return $order; }
}
