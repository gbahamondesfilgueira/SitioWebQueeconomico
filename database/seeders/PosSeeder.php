<?php

namespace Database\Seeders;

use App\Models\PosPaymentMethod;
use App\Models\PosTerminal;
use App\Models\Setting;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class PosSeeder extends Seeder
{
    public function run(): void
    {
        $warehouse = Warehouse::query()->where('code', 'POS01')->first()
            ?? Warehouse::query()->where('code', 'MAIN')->first()
            ?? Warehouse::query()->first();

        if ($warehouse) {
            PosTerminal::query()->updateOrCreate(['code' => 'POS01'], [
                'name' => 'POS Principal',
                'warehouse_id' => $warehouse->id,
                'location_id' => $warehouse->locations()->where('code', 'GENERAL')->value('id'),
                'description' => 'Terminal principal para ventas presenciales.',
                'is_active' => true,
            ]);
        }

        foreach ([
            ['name' => 'Efectivo', 'code' => 'cash', 'payment_type' => 'cash', 'requires_reference' => false],
            ['name' => 'Tarjeta Débito', 'code' => 'debit_card', 'payment_type' => 'debit_card', 'requires_reference' => true],
            ['name' => 'Tarjeta Crédito', 'code' => 'credit_card', 'payment_type' => 'credit_card', 'requires_reference' => true],
            ['name' => 'Transferencia', 'code' => 'bank_transfer', 'payment_type' => 'bank_transfer', 'requires_reference' => true],
        ] as $method) {
            PosPaymentMethod::query()->updateOrCreate(['code' => $method['code']], $method + ['is_active' => true]);
        }

        Setting::query()->updateOrCreate(['id' => 1], [
            'pos_manual_discount_without_approval' => 5,
            'pos_reservation_minutes' => 60,
            'pos_default_receipt_message' => 'Gracias por su compra',
        ]);
    }
}
