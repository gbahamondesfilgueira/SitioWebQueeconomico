<?php
namespace App\Jobs;
use App\Models\Integration;
use App\Services\Integrations\StockSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
class SyncIntegrationStockJob implements ShouldQueue { use Queueable; public function __construct(public Integration $integration) {} public function handle(StockSyncService $service): void { $service->syncAllStock($this->integration); } }
