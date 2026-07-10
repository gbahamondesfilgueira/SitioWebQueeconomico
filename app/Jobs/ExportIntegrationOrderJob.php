<?php
namespace App\Jobs;
use App\Models\Integration;
use App\Models\Order;
use App\Services\Integrations\OrderExportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
class ExportIntegrationOrderJob implements ShouldQueue { use Queueable; public function __construct(public Integration $integration, public Order $order) {} public function handle(OrderExportService $service): void { $service->exportOrder($this->integration, $this->order); } }
