<?php

use App\Services\InventoryService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Validation\ValidationException;

require dirname(__DIR__, 2).'/vendor/autoload.php';

$app = require dirname(__DIR__, 2).'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

try {
    app(InventoryService::class)->reserveStockLevel(
        stockLevelId: (int) $argv[1],
        quantity: 1,
        referenceType: 'mysql-concurrency-test',
        referenceId: (int) $argv[2],
        expiresAt: now()->addMinute(),
    );
    fwrite(STDOUT, 'reserved');
    exit(0);
} catch (ValidationException) {
    fwrite(STDOUT, 'rejected');
    exit(2);
} catch (Throwable $exception) {
    fwrite(STDERR, $exception::class);
    exit(3);
}
