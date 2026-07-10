<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\PriceList;
use App\Models\Promotion;
use Illuminate\Database\Seeder;

class CommercialEngineSeeder extends Seeder
{
    public function run(): void
    {
        PriceList::query()->update(['is_default' => false]);
        PriceList::query()->updateOrCreate(['code' => 'PUBLIC'], [
            'name' => 'Precio Público',
            'description' => 'Lista base para clientes generales.',
            'currency' => 'CLP',
            'is_default' => true,
            'is_active' => true,
        ]);

        Promotion::query()->updateOrCreate(['code' => 'CYBER'], [
            'name' => 'Oferta Cyber',
            'promotion_type' => 'percentage_discount',
            'priority' => 50,
            'discount_percentage' => 15,
            'is_stackable' => false,
            'is_active' => true,
        ]);

        Promotion::query()->updateOrCreate(['code' => 'FLASH'], [
            'name' => 'Flash Sale',
            'promotion_type' => 'fixed_discount',
            'priority' => 80,
            'discount_amount' => 2000,
            'is_stackable' => false,
            'is_active' => true,
        ]);

        Promotion::query()->updateOrCreate(['code' => 'QTY2'], [
            'name' => 'Compra 2 y paga menos',
            'promotion_type' => 'quantity_discount',
            'priority' => 30,
            'min_quantity' => 2,
            'discount_percentage' => 10,
            'is_stackable' => true,
            'is_active' => true,
        ]);

        Coupon::query()->updateOrCreate(['code' => 'BIENVENIDO10'], [
            'name' => 'Bienvenido 10%',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'usage_limit_per_customer' => 1,
            'is_active' => true,
        ]);

        Coupon::query()->updateOrCreate(['code' => 'ENVIOGRATIS'], [
            'name' => 'Envío gratis',
            'discount_type' => 'free_shipping',
            'discount_value' => null,
            'is_active' => true,
        ]);
    }
}
