<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\StockLevel;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockLevel>
 */
class StockLevelFactory extends Factory
{
    public function definition(): array
    {
        $physicalStock = fake()->numberBetween(0, 100);
        $reservedStock = fake()->numberBetween(0, $physicalStock);

        return [
            'warehouse_id' => Warehouse::factory(),
            'warehouse_location_id' => null,
            'product_id' => Product::factory(),
            'product_variant_id' => null,

            'physical_stock' => $physicalStock,
            'reserved_stock' => $reservedStock,
            'minimum_stock' => 5,
            'maximum_stock' => 150,
        ];
    }
}
