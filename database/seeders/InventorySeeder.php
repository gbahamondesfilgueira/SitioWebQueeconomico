<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = [
            ['name' => 'Bodega Principal', 'code' => 'MAIN', 'type' => 'main'],
            ['name' => 'Bodega Ecommerce', 'code' => 'ECOM', 'type' => 'ecommerce'],
            ['name' => 'Punto de Venta Principal', 'code' => 'POS01', 'type' => 'pos'],
        ];

        foreach ($warehouses as $warehouseData) {
            $warehouse = Warehouse::query()->updateOrCreate(['code' => $warehouseData['code']], [
                ...$warehouseData,
                'is_active' => true,
            ]);

            foreach ([
                ['name' => 'General', 'code' => 'GENERAL'],
                ['name' => 'Picking', 'code' => 'PICKING'],
                ['name' => 'Devoluciones', 'code' => 'DEV'],
                ['name' => 'Merma', 'code' => 'MERMA'],
            ] as $location) {
                $warehouse->locations()->updateOrCreate(['code' => $location['code']], [
                    ...$location,
                    'is_active' => true,
                ]);
            }
        }
    }
}
