<?php
namespace App\Jobs;
use App\Models\IntegrationLog;
use App\Services\Integrations\IntegrationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
class RetryFailedIntegrationLogJob implements ShouldQueue { use Queueable; public function __construct(public IntegrationLog $integrationLog) {} public function handle(IntegrationService $service): void { $service->retryFailedLog($this->integrationLog); } }
