<?php

namespace Tests\Feature;

use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarehouseTest extends TestCase
{
    use RefreshDatabase;

    public function test_warehouse_factory_can_create_a_warehouse(): void
    {
        $warehouse = Warehouse::factory()->create();

        $this->assertDatabaseHas('warehouses', [
            'id' => $warehouse->id,
            'code' => $warehouse->code,
        ]);

        $this->assertInstanceOf(Warehouse::class, $warehouse);
    }

    public function test_warehouse_can_store_its_basic_information(): void
    {
        $warehouse = Warehouse::factory()->create([
            'name' => 'Bodega Central',
            'code' => 'WH-CENTRAL',
            'type' => 'branch',
            'city' => 'Santiago',
            'region' => 'Metropolitana',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('warehouses', [
            'id' => $warehouse->id,
            'name' => 'Bodega Central',
            'code' => 'WH-CENTRAL',
            'type' => 'branch',
            'city' => 'Santiago',
            'region' => 'Metropolitana',
        ]);
    }

    public function test_warehouse_active_status_is_cast_to_boolean(): void
    {
        $warehouse = Warehouse::factory()->create([
            'is_active' => true,
        ]);

        $warehouse->refresh();

        $this->assertTrue($warehouse->is_active);
    }

    public function test_warehouse_can_be_soft_deleted(): void
    {
        $warehouse = Warehouse::factory()->create();

        $warehouse->delete();

        $this->assertSoftDeleted('warehouses', [
            'id' => $warehouse->id,
        ]);
    }
}