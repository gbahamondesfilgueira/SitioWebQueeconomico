<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SystemClearExpiredReservations extends Command
{
    protected $signature = 'system:clear-expired-reservations';
    protected $description = 'Expira reservas POS antiguas.';

    public function handle(): int
    {
        Artisan::call('pos:expire-reservations');
        $this->line(Artisan::output());
        return self::SUCCESS;
    }
}
