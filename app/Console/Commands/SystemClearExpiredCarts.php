<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SystemClearExpiredCarts extends Command
{
    protected $signature = 'system:clear-expired-carts';
    protected $description = 'Expira carritos antiguos y libera reservas.';

    public function handle(): int
    {
        Artisan::call('carts:expire');
        $this->line(Artisan::output());
        return self::SUCCESS;
    }
}
