<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockLevel;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockLevelTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_level_factory_can_create_a_stock_level(): void
    {
        $stockLevel = StockLevel::factory()->create();

        $this->assertDatabaseHas('stock_levels', [
            'id' => $stockLevel->id,
            'warehouse_id' => $stockLevel->warehouse_id,
            'product_id' => $stockLevel->product_id,
        ]);

        $this->assertInstanceOf(StockLevel::class, $stockLevel);
    }

    public function test_stock_level_belongs_to_a_warehouse_and_product(): void
    {
        $warehouse = Warehouse::factory()->create();
        $product = Product::factory()->create();

        $stockLevel = StockLevel::factory()->create([
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
        ]);

        $this->assertSame(
            $warehouse->id,
            $stockLevel->warehouse->id
        );

        $this->assertSame(
            $product->id,
            $stockLevel->product->id
        );
    }

    public function test_available_stock_is_physical_stock_minus_reserved_stock(): void
    {
        $stockLevel = StockLevel::factory()->create([
            'physical_stock' => 100,
            'reserved_stock' => 25,
        ]);

        $this->assertSame(75, $stockLevel->available_stock);
    }

    public function test_available_stock_never_returns_a_negative_value(): void
    {
        $stockLevel = StockLevel::factory()->create([
            'physical_stock' => 10,
            'reserved_stock' => 20,
        ]);

        $this->assertSame(0, $stockLevel->available_stock);
    }

    public function test_stock_values_are_cast_to_integers(): void
    {
        $stockLevel = StockLevel::factory()->create([
            'physical_stock' => 50,
            'reserved_stock' => 10,
            'minimum_stock' => 5,
            'maximum_stock' => 150,
        ]);

        $stockLevel->refresh();

        $this->assertSame(50, $stockLevel->physical_stock);
        $this->assertSame(10, $stockLevel->reserved_stock);
        $this->assertSame(5, $stockLevel->minimum_stock);
        $this->assertSame(150, $stockLevel->maximum_stock);
    }
}