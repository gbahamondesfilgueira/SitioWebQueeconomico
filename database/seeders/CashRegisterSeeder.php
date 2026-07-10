<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class CashRegisterSeeder extends Seeder
{
    public function run(): void
    {
        Setting::query()->updateOrCreate(['id' => 1], [
            'cash_require_open_session' => true,
            'cash_allow_negative_difference' => true,
            'cash_max_manual_expense_without_admin' => 20000,
            'cash_default_currency' => 'CLP',
        ]);
    }
}
