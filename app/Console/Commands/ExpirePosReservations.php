<?php

namespace App\Console\Commands;

use App\Services\PosService;
use Illuminate\Console\Command;

class ExpirePosReservations extends Command
{
    protected $signature = 'pos:expire-reservations';
    protected $description = 'Libera reservas POS vencidas y devuelve el stock reservado al disponible.';

    public function handle(PosService $posService): int
    {
        $count = $posService->expireReservations();
        $this->info("Reservas POS vencidas liberadas: {$count}");
        return self::SUCCESS;
    }
}
