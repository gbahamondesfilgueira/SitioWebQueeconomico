<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Warehouse>
 */
class WarehouseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Bodega '.fake()->city(),
            'code' => 'WH-'.Str::upper(Str::random(8)),
            'type' => 'branch',
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'region' => 'Metropolitana',
            'commune' => fake()->city(),
            'phone' => null,
            'email' => fake()->safeEmail(),
            'is_active' => true,
        ];
    }
}
