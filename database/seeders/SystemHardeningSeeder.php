<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SystemHardeningSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::query()->updateOrCreate(
            ['rut' => ''],
            [
                'name' => 'Qué Económico',
                'legal_name' => 'Qué Económico',
                'email' => 'contacto@queeconomico.local',
                'currency' => 'CLP',
                'tax_percentage' => 19,
                'is_active' => true,
            ]
        );

        Branch::query()->updateOrCreate(
            ['code' => 'MAIN'],
            ['company_id' => $company->id, 'name' => 'Sucursal Principal', 'is_active' => true]
        );

        foreach ([
            ['name' => 'Peso Chileno', 'code' => 'CLP', 'symbol' => '$', 'decimal_places' => 0, 'is_default' => true],
            ['name' => 'Dólar', 'code' => 'USD', 'symbol' => 'US$', 'decimal_places' => 2, 'is_default' => false],
            ['name' => 'Peso Uruguayo', 'code' => 'UYU', 'symbol' => '$U', 'decimal_places' => 0, 'is_default' => false],
        ] as $currency) {
            Currency::query()->updateOrCreate(['code' => $currency['code']], $currency + ['is_active' => true]);
        }

        $permissions = [
            ['name' => 'Gestionar usuarios', 'slug' => 'manage_users', 'module' => 'users'],
            ['name' => 'Gestionar productos', 'slug' => 'manage_products', 'module' => 'products'],
            ['name' => 'Gestionar inventario', 'slug' => 'manage_inventory', 'module' => 'inventory'],
            ['name' => 'Gestionar pedidos', 'slug' => 'manage_orders', 'module' => 'orders'],
            ['name' => 'Gestionar POS', 'slug' => 'manage_pos', 'module' => 'pos'],
            ['name' => 'Gestionar caja', 'slug' => 'manage_cash', 'module' => 'cash'],
            ['name' => 'Gestionar envíos', 'slug' => 'manage_shipping', 'module' => 'shipping'],
            ['name' => 'Gestionar integraciones', 'slug' => 'manage_integrations', 'module' => 'integrations'],
            ['name' => 'Ver reportes', 'slug' => 'view_reports', 'module' => 'reports'],
            ['name' => 'Gestionar configuración', 'slug' => 'manage_settings', 'module' => 'settings'],
            ['name' => 'Ver auditoría', 'slug' => 'view_audit_logs', 'module' => 'audit'],
        ];

        foreach ($permissions as $permission) {
            Permission::query()->updateOrCreate(['slug' => $permission['slug']], $permission);
        }

        $superAdmin = Role::query()->where('slug', 'super-admin')->first();
        $admin = Role::query()->where('slug', 'administrador')->first();
        $bodeguero = Role::query()->where('slug', 'bodeguero')->first();
        $vendedor = Role::query()->where('slug', 'vendedor-pos')->first();

        $allPermissions = Permission::query()->pluck('id');
        $superAdmin?->permissions()->sync($allPermissions);
        $admin?->permissions()->sync($allPermissions);
        $bodeguero?->permissions()->sync(Permission::query()->whereIn('slug', ['manage_inventory', 'view_reports'])->pluck('id'));
        $vendedor?->permissions()->sync(Permission::query()->whereIn('slug', ['manage_pos', 'manage_cash'])->pluck('id'));

        Setting::query()->updateOrCreate(['id' => 1], [
            'company_id' => $company->id,
            'default_locale' => 'es',
            'available_locales' => ['es', 'en'],
        ]);
    }
}
