<?php

namespace Database\Seeders;

use App\Models\CustomerProfile;
use App\Models\CustomerTag;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CustomerCrmSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Mayorista', 'VIP', 'Frecuente', 'Moroso', 'Empresa', 'Influencer'] as $name) {
            CustomerTag::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'description' => "Etiqueta {$name}", 'is_active' => true],
            );
        }

        $role = Role::query()->where('slug', 'cliente')->first();
        $user = User::query()->firstOrCreate(
            ['email' => 'cliente@erp.local'],
            [
                'name' => 'Cliente Demo',
                'password' => 'Cliente1234',
                'role_id' => $role?->id,
                'is_active' => true,
            ],
        );

        CustomerProfile::query()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'customer_type' => 'individual',
                'first_name' => 'Cliente',
                'last_name' => 'Demo',
                'email' => $user->email,
                'phone' => '+56 9 0000 0000',
                'newsletter' => true,
                'accept_promotions' => true,
                'accept_email_marketing' => true,
                'consent_accepted_at' => now(),
                'is_active' => true,
            ],
        );
    }
}
