<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockLevel;
use App\Models\StockMovement;
use App\Models\StockReservation;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Process\Process;
use Tests\TestCase;

class MySqlStockConcurrencyTest extends TestCase
{
    public function test_two_database_processes_cannot_reserve_the_same_last_unit(): void
    {
        if (getenv('RUN_MYSQL_CONCURRENCY_TESTS') !== 'true') {
            $this->markTestSkipped('Define RUN_MYSQL_CONCURRENCY_TESTS=true para ejecutar esta prueba contra MySQL.');
        }
        if (DB::connection()->getDriverName() !== 'mysql') {
            $this->markTestSkipped('La prueba de bloqueos requiere MySQL/InnoDB.');
        }
        if (! str_contains(strtolower((string) DB::connection()->getDatabaseName()), 'test')) {
            $this->markTestSkipped('Por seguridad, la base MySQL debe incluir "test" en su nombre.');
        }
        if (! Schema::hasTable('stock_levels')) {
            $this->markTestSkipped('La base MySQL de pruebas debe estar migrada antes de ejecutar concurrencia.');
        }

        $warehouse = Warehouse::factory()->create(['is_active' => true]);
        $location = $warehouse->locations()->create(['name' => 'Concurrencia', 'code' => 'CONCURRENCY', 'is_active' => true, 'is_sellable' => true]);
        $product = Product::factory()->create();
        $level = StockLevel::query()->create([
            'warehouse_id' => $warehouse->id,
            'warehouse_location_id' => $location->id,
            'product_id' => $product->id,
            'physical_stock' => 1,
            'reserved_stock' => 0,
            'minimum_stock' => 0,
        ]);
        $reference = random_int(100000, 999999);

        try {
            $worker = base_path('tests/Support/reserve_stock_worker.php');
            $first = new Process([PHP_BINARY, $worker, (string) $level->id, (string) $reference], base_path(), null, null, 20);
            $second = new Process([PHP_BINARY, $worker, (string) $level->id, (string) ($reference + 1)], base_path(), null, null, 20);

            $first->start();
            $second->start();
            $first->wait();
            $second->wait();

            $this->assertSame([0, 2], collect([$first->getExitCode(), $second->getExitCode()])->sort()->values()->all(), $first->getErrorOutput().$second->getErrorOutput());
            $this->assertSame(1.0, (float) $level->refresh()->reserved_stock);
            $this->assertSame(1, StockReservation::query()->where('reference_type', 'mysql-concurrency-test')->whereIn('reference_id', [$reference, $reference + 1])->where('status', 'active')->count());
        } finally {
            StockMovement::query()->where('reference_type', 'mysql-concurrency-test')->whereIn('reference_id', [$reference, $reference + 1])->delete();
            StockReservation::query()->where('reference_type', 'mysql-concurrency-test')->whereIn('reference_id', [$reference, $reference + 1])->delete();
            $level->delete();
            $product->forceDelete();
            $warehouse->forceDelete();
        }
    }
}
