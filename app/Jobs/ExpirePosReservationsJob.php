<?php

namespace App\Jobs;

use App\Models\PosReservation;
use App\Services\InventoryService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ExpirePosReservationsJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;
    public int $timeout = 120;

    public function handle(InventoryService $inventory): void
    {
        PosReservation::query()
            ->with('items.stockReservation')
            ->where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->chunkById(100, function ($reservations) use ($inventory) {
                foreach ($reservations as $reservation) {
                    foreach ($reservation->items as $item) {
                        if ($item->stockReservation) {
                            $inventory->releaseReservation($item->stockReservation);
                        }
                    }
                    $reservation->update(['status' => 'expired']);
                }
            });
    }
}
