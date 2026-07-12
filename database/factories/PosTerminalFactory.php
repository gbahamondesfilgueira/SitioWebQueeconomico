<?php

namespace Database\Factories;

use App\Models\PosTerminal;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PosTerminal>
 */
class PosTerminalFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Terminal POS '.fake()->numberBetween(1, 9999),
            'code' => 'POS-'.Str::upper(Str::random(8)),
            'warehouse_id' => Warehouse::factory(),
            'location_id' => null,
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
