<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\AuditLogger;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendOrderNotificationJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public int $orderId)
    {
    }

    public function handle(): void
    {
        $order = Order::query()->find($this->orderId);
        AuditLogger::record('queued', 'orders', 'Notificación de pedido preparada: '.($order?->order_number ?? $this->orderId));
    }
}
