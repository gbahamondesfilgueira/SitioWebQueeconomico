<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\ShippingCarrier;
use App\Models\ShippingRate;
use App\Models\ShippingService;
use App\Models\ShippingZone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShippingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::current()->update([
            'shipping_volumetric_factor' => 4000,
            'default_pickup_enabled' => true,
            'default_fixed_shipping_price' => 4990,
        ]);

        foreach (['Blue Express', 'Chilexpress', 'Starken', 'CorreosChile', 'Shipit', 'Envíame'] as $name) {
            $carrier = ShippingCarrier::query()->updateOrCreate(
                ['code' => Str::slug($name, '_')],
                [
                    'name' => $name,
                    'tracking_url_template' => 'https://tracking.example.com/{tracking_number}',
                    'is_active' => true,
                    'supports_api' => in_array($name, ['Shipit', 'Envíame'], true),
                ],
            );

            foreach ([['Envío estándar', 'standard', 'standard', 1, 3], ['Envío express', 'express', 'express', 1, 2], ['Retiro en tienda', 'pickup', 'pickup', 0, 1]] as [$serviceName, $code, $type, $min, $max]) {
                ShippingService::query()->updateOrCreate(
                    ['shipping_carrier_id' => $carrier->id, 'code' => $code],
                    ['name' => $serviceName, 'service_type' => $type, 'estimated_days_min' => $min, 'estimated_days_max' => $max, 'is_active' => true],
                );
            }
        }

        $zones = [
            ['Chile', 'Chile', null],
            ['Región Metropolitana', 'Chile', 'Región Metropolitana'],
            ['Valparaíso', 'Chile', 'Valparaíso'],
            ['Biobío', 'Chile', 'Biobío'],
            ['Coquimbo', 'Chile', 'Coquimbo'],
            ['Antofagasta', 'Chile', 'Antofagasta'],
        ];

        foreach ($zones as [$name, $country, $region]) {
            ShippingZone::query()->updateOrCreate(['name' => $name], ['country' => $country, 'region' => $region, 'is_active' => true]);
        }

        $carrier = ShippingCarrier::query()->where('code', 'chilexpress')->first() ?? ShippingCarrier::query()->first();
        $standard = $carrier?->services()->where('code', 'standard')->first();
        foreach (ShippingZone::query()->get() as $zone) {
            ShippingRate::query()->updateOrCreate(
                ['shipping_carrier_id' => $carrier->id, 'shipping_service_id' => $standard?->id, 'shipping_zone_id' => $zone->id, 'min_weight' => 0, 'max_weight' => 5],
                ['price' => $zone->region ? 4990 : 6990, 'currency' => 'CLP', 'is_active' => true],
            );
        }
    }
}
