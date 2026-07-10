<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SystemOptimizeProduction extends Command
{
    protected $signature = 'system:optimize-production';
    protected $description = 'Ejecuta optimizaciones seguras para producción.';

    public function handle(): int
    {
        foreach (['config:cache', 'route:cache', 'view:cache', 'event:cache'] as $command) {
            Artisan::call($command);
            $this->info($command.' OK');
        }

        return self::SUCCESS;
    }
}
