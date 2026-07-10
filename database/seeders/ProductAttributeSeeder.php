<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class ProductAttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [
            'Color' => ['type' => 'color', 'values' => ['Negro', 'Blanco', 'Dorado', 'Plateado', 'Café', 'Beige']],
            'Talla' => ['type' => 'size', 'values' => ['35', '36', '37', '38', '39', '40', '41']],
            'Material' => ['type' => 'select', 'values' => ['Acero quirúrgico', 'Acero inoxidable', 'Sintético', 'Goma', 'Cuero sintético']],
        ];

        foreach ($attributes as $index => $name) {
            $attribute = Attribute::query()->updateOrCreate([
                'slug' => str($index)->slug()->toString(),
            ], [
                'name' => $index,
                'type' => $name['type'],
                'is_active' => true,
                'sort_order' => array_search($index, array_keys($attributes), true) + 1,
            ]);

            foreach ($name['values'] as $valueIndex => $value) {
                $attribute->values()->updateOrCreate([
                    'slug' => str($value)->slug()->toString(),
                ], [
                    'value' => $value,
                    'sort_order' => $valueIndex + 1,
                    'is_active' => true,
                ]);
            }
        }
    }
}
