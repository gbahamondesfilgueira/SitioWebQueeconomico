<?php
namespace App\Console\Commands;
use App\Jobs\SyncIntegrationStockJob;
use App\Models\Integration;
use Illuminate\Console\Command;
class SyncIntegrationStock extends Command { protected $signature='integrations:sync-stock {integration?}'; protected $description='Despacha sincronización mock de stock.'; public function handle(): int { $q=Integration::query()->where('is_active',true); if($this->argument('integration')) $q->where('code',$this->argument('integration')); $q->get()->each(fn($i)=>SyncIntegrationStockJob::dispatch($i)); $this->info('Sincronización de stock despachada.'); return self::SUCCESS; } }
