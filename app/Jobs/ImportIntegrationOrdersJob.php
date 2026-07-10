<?php
namespace App\Jobs;
use App\Models\Integration;
use App\Services\Integrations\OrderImportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
class ImportIntegrationOrdersJob implements ShouldQueue { use Queueable; public function __construct(public Integration $integration) {} public function handle(OrderImportService $service): void { $service->importOrders($this->integration); } }
