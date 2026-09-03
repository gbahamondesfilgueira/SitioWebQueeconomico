<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = [
            ['name' => 'Bodega Principal', 'code' => 'MAIN', 'type' => 'main', 'region' => 'metropolitana', 'is_central' => false, 'fulfillment_priority' => 50],
            ['name' => 'Bodega Central Ecommerce', 'code' => 'ECOM', 'type' => 'ecommerce', 'region' => 'metropolitana', 'is_central' => true, 'fulfillment_priority' => 1],
            ['name' => 'Bodega Regional Biobío', 'code' => 'BIOBIO', 'type' => 'branch', 'region' => 'biobio', 'is_central' => false, 'fulfillment_priority' => 10],
            ['name' => 'Punto de Venta Principal', 'code' => 'POS01', 'type' => 'pos', 'region' => 'metropolitana', 'is_central' => false, 'fulfillment_priority' => 100],
        ];

        foreach ($warehouses as $warehouseData) {
            $warehouse = Warehouse::query()->updateOrCreate(['code' => $warehouseData['code']], [
                ...$warehouseData,
                'is_active' => true,
            ]);

            foreach ([
                ['name' => 'General', 'code' => 'GENERAL', 'is_sellable' => true],
                ['name' => 'Picking', 'code' => 'PICKING', 'is_sellable' => true],
                ['name' => 'Devoluciones', 'code' => 'DEV', 'is_sellable' => false],
                ['name' => 'Merma', 'code' => 'MERMA', 'is_sellable' => false],
            ] as $location) {
                $warehouse->locations()->updateOrCreate(['code' => $location['code']], [
                    ...$location,
                    'is_active' => true,
                ]);
            }
        }
    }
}
