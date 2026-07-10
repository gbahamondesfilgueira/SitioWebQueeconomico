<?php

namespace App\Console\Commands;

use App\Services\CartService;
use Illuminate\Console\Command;

class ExpireCartsCommand extends Command
{
    protected $signature = 'carts:expire';

    protected $description = 'Expire old active carts and release stock reservations.';

    public function handle(CartService $cartService): int
    {
        $count = $cartService->expireOldCarts();
        $this->info("Carritos expirados: {$count}");

        return self::SUCCESS;
    }
}
