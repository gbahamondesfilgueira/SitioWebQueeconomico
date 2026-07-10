<?php
namespace App\Console\Commands;
use App\Jobs\ProcessWebhookEventJob;
use App\Models\WebhookEvent;
use Illuminate\Console\Command;
class ProcessIntegrationWebhooks extends Command { protected $signature='integrations:process-webhooks'; protected $description='Despacha webhooks recibidos pendientes.'; public function handle(): int { WebhookEvent::query()->where('status','received')->get()->each(fn($e)=>ProcessWebhookEventJob::dispatch($e)); $this->info('Webhooks pendientes despachados.'); return self::SUCCESS; } }
