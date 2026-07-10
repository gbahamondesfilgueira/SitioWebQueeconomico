<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Acceso total al sistema.'],
            ['name' => 'Administrador', 'slug' => 'administrador', 'description' => 'Administra la operación general.'],
            ['name' => 'Vendedor POS', 'slug' => 'vendedor-pos', 'description' => 'Acceso futuro al punto de venta.'],
            ['name' => 'Bodeguero', 'slug' => 'bodeguero', 'description' => 'Acceso futuro a inventario y bodega.'],
            ['name' => 'Cliente', 'slug' => 'cliente', 'description' => 'Acceso a cuenta cliente.'],
        ];

        foreach ($roles as $role) {
            Role::query()->updateOrCreate(
                ['slug' => $role['slug']],
                $role,
            );
        }

        User::query()->updateOrCreate([
            'email' => 'admin@erp.local',
        ], [
            'name' => 'Super Admin',
            'role_id' => Role::query()->where('slug', 'super-admin')->value('id'),
            'password' => 'Admin1234',
            'is_active' => true,
            'phone' => null,
        ]);

        Setting::query()->updateOrCreate(['id' => 1], [
            'store_name' => 'Qué Económico',
            'currency' => 'CLP',
            'tax_percentage' => 19,
            'maintenance_mode' => false,
        ]);

        $this->call(MasterCatalogSeeder::class);
        $this->call(ProductAttributeSeeder::class);
        $this->call(InventorySeeder::class);
        $this->call(CommercialEngineSeeder::class);
        $this->call(CustomerCrmSeeder::class);
        $this->call(ShippingSeeder::class);
        $this->call(PosSeeder::class);
        $this->call(CashRegisterSeeder::class);
        $this->call(IntegrationSeeder::class);
        $this->call(SystemHardeningSeeder::class);
    }
}
