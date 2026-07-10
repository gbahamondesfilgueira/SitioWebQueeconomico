<?php
namespace App\Console\Commands;
use App\Jobs\ImportIntegrationOrdersJob;
use App\Models\Integration;
use Illuminate\Console\Command;
class ImportIntegrationOrders extends Command { protected $signature='integrations:import-orders {integration?}'; protected $description='Despacha importación mock de pedidos.'; public function handle(): int { $q=Integration::query()->where('is_active',true); if($this->argument('integration')) $q->where('code',$this->argument('integration')); $q->get()->each(fn($i)=>ImportIntegrationOrdersJob::dispatch($i)); $this->info('Importación de pedidos despachada.'); return self::SUCCESS; } }
