<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockLevel;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_have_stock_in_a_warehouse(): void
    {
        $product = Product::factory()->create();

        $warehouse = Warehouse::factory()->create();

        $stockLevel = StockLevel::factory()->create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'physical_stock' => 100,
            'reserved_stock' => 20,
        ]);

        $this->assertDatabaseHas('stock_levels', [
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'physical_stock' => 100,
            'reserved_stock' => 20,
        ]);

        $this->assertSame(
            $product->id,
            $stockLevel->product->id
        );

        $this->assertSame(
            $warehouse->id,
            $stockLevel->warehouse->id
        );

        $this->assertSame(
            80,
            $stockLevel->available_stock
        );
    }

    public function test_same_product_can_have_stock_in_different_warehouses(): void
    {
        $product = Product::factory()->create();

        $warehouseOne = Warehouse::factory()->create();
        $warehouseTwo = Warehouse::factory()->create();

        $stockOne = StockLevel::factory()->create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouseOne->id,
            'physical_stock' => 50,
            'reserved_stock' => 10,
        ]);

        $stockTwo = StockLevel::factory()->create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouseTwo->id,
            'physical_stock' => 30,
            'reserved_stock' => 5,
        ]);

        $this->assertSame(40, $stockOne->available_stock);
        $this->assertSame(25, $stockTwo->available_stock);

        $this->assertDatabaseCount('stock_levels', 2);
    }

    public function test_reserved_stock_reduces_available_stock(): void
    {
        $stockLevel = StockLevel::factory()->create([
            'physical_stock' => 25,
            'reserved_stock' => 5,
        ]);

        $this->assertSame(20, $stockLevel->available_stock);

        $stockLevel->update([
            'reserved_stock' => 15,
        ]);

        $stockLevel->refresh();

        $this->assertSame(10, $stockLevel->available_stock);
    }

    public function test_available_stock_never_becomes_negative(): void
    {
        $stockLevel = StockLevel::factory()->create([
            'physical_stock' => 5,
            'reserved_stock' => 10,
        ]);

        $this->assertSame(0, $stockLevel->available_stock);
    }
}