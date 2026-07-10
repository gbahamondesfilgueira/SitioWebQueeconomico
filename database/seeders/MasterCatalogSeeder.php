<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\MeasurementUnit;
use App\Models\OriginCountry;
use App\Models\Tax;
use Illuminate\Database\Seeder;

class MasterCatalogSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Calzado', 'Ropa', 'Accesorios', 'Belleza', 'Hogar', 'Tecnología'] as $index => $name) {
            Category::query()->updateOrCreate([
                'slug' => str($name)->slug()->toString(),
            ], [
                'name' => $name,
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }

        foreach (['Genérica', 'LikeShop'] as $name) {
            Brand::query()->updateOrCreate([
                'slug' => str($name)->slug()->toString(),
            ], [
                'name' => $name,
                'is_active' => true,
            ]);
        }

        $units = [
            ['name' => 'Unidad', 'code' => 'un', 'type' => 'quantity'],
            ['name' => 'Kilogramo', 'code' => 'kg', 'type' => 'weight'],
            ['name' => 'Gramo', 'code' => 'g', 'type' => 'weight'],
            ['name' => 'Centímetro', 'code' => 'cm', 'type' => 'dimension'],
            ['name' => 'Metro', 'code' => 'm', 'type' => 'dimension'],
        ];

        foreach ($units as $unit) {
            MeasurementUnit::query()->updateOrCreate(['code' => $unit['code']], $unit);
        }

        Tax::query()->update(['is_default' => false]);
        Tax::query()->updateOrCreate([
            'name' => 'IVA',
        ], [
            'percentage' => 19,
            'is_default' => true,
            'is_active' => true,
        ]);

        $countries = [
            ['name' => 'Chile', 'iso_code' => 'CL'],
            ['name' => 'China', 'iso_code' => 'CN'],
            ['name' => 'Uruguay', 'iso_code' => 'UY'],
            ['name' => 'Argentina', 'iso_code' => 'AR'],
            ['name' => 'Brasil', 'iso_code' => 'BR'],
        ];

        foreach ($countries as $country) {
            OriginCountry::query()->updateOrCreate(['iso_code' => $country['iso_code']], [
                ...$country,
                'is_active' => true,
            ]);
        }
    }
}
