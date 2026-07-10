<?php

namespace App\Console\Commands;

use App\Services\CacheService;
use Illuminate\Console\Command;

class SystemCacheRefresh extends Command
{
    protected $signature = 'system:cache-refresh';
    protected $description = 'Limpia y recalienta caché principal.';

    public function handle(CacheService $cache): int
    {
        $cache->clearStoreCache();
        $cache->rememberSettings();
        $cache->rememberHomeSections();
        $this->info('Caché principal actualizada.');
        return self::SUCCESS;
    }
}
