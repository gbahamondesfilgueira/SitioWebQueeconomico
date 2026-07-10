<?php

namespace App\Jobs;

use App\Services\CartService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ExpireCartReservationsJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;
    public int $timeout = 120;

    public function handle(CartService $cartService): void
    {
        $cartService->expireOldCarts();
    }
}
