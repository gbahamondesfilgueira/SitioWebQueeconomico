<?php
namespace App\Jobs;
use App\Models\Integration;
use App\Services\Integrations\PriceSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
class SyncIntegrationPricesJob implements ShouldQueue { use Queueable; public function __construct(public Integration $integration) {} public function handle(PriceSyncService $service): void { $service->syncAllPrices($this->integration); } }
