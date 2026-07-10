<?php
namespace App\Console\Commands;
use App\Jobs\RetryFailedIntegrationLogJob;
use App\Models\IntegrationLog;
use Illuminate\Console\Command;
class RetryFailedIntegrations extends Command { protected $signature='integrations:retry-failed'; protected $description='Reintenta logs fallidos seguros.'; public function handle(): int { IntegrationLog::query()->where('status','failed')->get()->each(fn($l)=>RetryFailedIntegrationLogJob::dispatch($l)); $this->info('Reintentos despachados.'); return self::SUCCESS; } }
