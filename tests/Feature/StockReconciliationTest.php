<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockLevel;
use App\Models\StockReservation;
use App\Models\SystemHealthCheck;
use App\Models\Warehouse;
use App\Services\StockReconciliationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockReconciliationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_detects_and_repairs_a_reservation_counter_mismatch(): void
    {
        $level = $this->stockLevel(physical: 10, reserved: 4);

        $before = app(StockReconciliationService::class)->scan();

        $this->assertCount(1, $before);
        $this->assertContains('reservation_counter_mismatch', $before->first()['types']);

        $result = app(StockReconciliationService::class)->reconcile(fixCounters: true);

        $this->assertSame(1, $result['fixed']);
        $this->assertSame(0.0, (float) $level->refresh()->reserved_stock);
        $this->assertSame('ok', $result['health']['status']);
        $this->assertSame('ok', SystemHealthCheck::query()->latest('checked_at')->value('status'));
    }

    public function test_it_raises_a_critical_alert_when_active_reservations_exceed_physical_stock(): void
    {
        $level = $this->stockLevel(physical: 2, reserved: 3);
        StockReservation::query()->create([
            'warehouse_id' => $level->warehouse_id,
            'warehouse_location_id' => $level->warehouse_location_id,
            'product_id' => $level->product_id,
            'quantity' => 3,
            'status' => 'active',
        ]);

        $health = app(StockReconciliationService::class)->healthCheck();

        $this->assertSame('error', $health['status']);
        $this->assertContains('overcommitted_stock', $health['metadata']['issues'][0]['types']);
    }

    public function test_it_can_release_expired_reservations_and_reconcile_the_counter(): void
    {
        $level = $this->stockLevel(physical: 5, reserved: 2);
        $reservation = StockReservation::query()->create([
            'warehouse_id' => $level->warehouse_id,
            'warehouse_location_id' => $level->warehouse_location_id,
            'product_id' => $level->product_id,
            'quantity' => 2,
            'status' => 'active',
            'expires_at' => now()->subMinute(),
        ]);

        $result = app(StockReconciliationService::class)->reconcile(releaseExpired: true);

        $this->assertSame(1, $result['released']);
        $this->assertSame('released', $reservation->refresh()->status);
        $this->assertSame(0.0, (float) $level->refresh()->reserved_stock);
        $this->assertSame('ok', $result['health']['status']);
    }

    private function stockLevel(int $physical, int $reserved): StockLevel
    {
        $warehouse = Warehouse::factory()->create(['is_active' => true]);
        $location = $warehouse->locations()->create([
            'name' => 'General',
            'code' => 'GENERAL',
            'is_active' => true,
            'is_sellable' => true,
        ]);
        $product = Product::factory()->create();

        return StockLevel::query()->create([
            'warehouse_id' => $warehouse->id,
            'warehouse_location_id' => $location->id,
            'product_id' => $product->id,
            'physical_stock' => $physical,
            'reserved_stock' => $reserved,
            'minimum_stock' => 0,
        ]);
    }
}
