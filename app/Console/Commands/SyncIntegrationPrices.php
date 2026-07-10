<?php
namespace App\Console\Commands;
use App\Jobs\SyncIntegrationPricesJob;
use App\Models\Integration;
use Illuminate\Console\Command;
class SyncIntegrationPrices extends Command { protected $signature='integrations:sync-prices {integration?}'; protected $description='Despacha sincronización mock de precios.'; public function handle(): int { $q=Integration::query()->where('is_active',true); if($this->argument('integration')) $q->where('code',$this->argument('integration')); $q->get()->each(fn($i)=>SyncIntegrationPricesJob::dispatch($i)); $this->info('Sincronización de precios despachada.'); return self::SUCCESS; } }
