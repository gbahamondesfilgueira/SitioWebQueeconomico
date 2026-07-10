<?php

namespace Database\Seeders;

use App\Models\ApiClient;
use App\Models\Integration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class IntegrationSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['WooCommerce', 'woocommerce', 'ecommerce', 'WooCommerce'],
            ['Mercado Libre', 'mercado_libre', 'marketplace', 'Mercado Libre'],
            ['Falabella Seller', 'falabella', 'marketplace', 'Falabella Seller'],
            ['Shopify', 'shopify', 'ecommerce', 'Shopify'],
            ['Webpay', 'webpay', 'payment', 'Webpay'],
            ['Flow', 'flow', 'payment', 'Flow'],
            ['Mercado Pago', 'mercado_pago', 'payment', 'Mercado Pago'],
            ['Blue Express', 'blue_express', 'shipping', 'Blue Express'],
            ['Chilexpress', 'chilexpress', 'shipping', 'Chilexpress'],
            ['Starken', 'starken', 'shipping', 'Starken'],
            ['Shipit', 'shipit', 'shipping', 'Shipit'],
            ['Envíame', 'enviame', 'shipping', 'Envíame'],
        ];

        foreach ($items as [$name, $code, $type, $provider]) {
            Integration::query()->updateOrCreate(['code' => $code], [
                'name' => $name,
                'provider_type' => $type,
                'provider_name' => $provider,
                'environment' => 'sandbox',
                'status' => 'inactive',
                'is_active' => false,
                'settings' => [
                    'stock_sync_enabled' => false,
                    'price_sync_enabled' => false,
                    'stock_sync_strategy' => 'available_stock',
                    'round_prices' => true,
                ],
            ]);
        }

        ApiClient::query()->updateOrCreate(['code' => 'demo_inactive'], [
            'name' => 'Demo API Client Inactivo',
            'description' => 'Cliente de ejemplo. Crear uno nuevo para obtener token plano.',
            'token_hash' => Hash::make('disabled'),
            'permissions' => ['read_products', 'write_products', 'read_stock', 'write_stock', 'read_orders', 'write_orders', 'read_customers', 'write_customers', 'read_shipments', 'write_shipments'],
            'rate_limit_per_minute' => 60,
            'is_active' => false,
        ]);
    }
}
