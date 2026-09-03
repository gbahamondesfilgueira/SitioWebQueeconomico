<?php

namespace App\Console\Commands;

use App\Services\StockReconciliationService;
use Illuminate\Console\Command;

class ReconcileStock extends Command
{
    protected $signature = 'inventory:reconcile
        {--fix : Sincroniza los contadores reserved_stock con las reservas activas}
        {--release-expired : Libera reservas activas cuya fecha de vencimiento ya pasó}';

    protected $description = 'Compara el stock registrado con las reservas exactas por bodega y ubicación.';

    public function handle(StockReconciliationService $reconciliation): int
    {
        $result = $reconciliation->reconcile(
            fixCounters: (bool) $this->option('fix'),
            releaseExpired: (bool) $this->option('release-expired'),
        );

        $this->line($result['health']['message']);
        $this->line("Contadores corregidos: {$result['fixed']}");
        $this->line("Reservas vencidas liberadas: {$result['released']}");

        return $result['health']['status'] === 'error' ? self::FAILURE : self::SUCCESS;
    }
}
