<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'store_name',
        'legal_name',
        'rut',
        'email',
        'phone',
        'address',
        'currency',
        'tax_percentage',
        'logo_path',
        'maintenance_mode',
        'shipping_volumetric_factor',
        'default_pickup_enabled',
        'default_fixed_shipping_price',
        'pos_manual_discount_without_approval',
        'pos_reservation_minutes',
        'pos_default_receipt_message',
        'cash_require_open_session',
        'cash_allow_negative_difference',
        'cash_max_manual_expense_without_admin',
        'cash_default_currency',
        'company_id',
        'default_locale',
        'available_locales',
        'google_login_enabled',
        'google_client_id',
        'google_client_secret',
        'google_redirect_uri',
        'google_search_console_verification',
        'google_analytics_measurement_id',
        'google_tag_manager_id',
    ];

    protected function casts(): array
    {
        return [
            'maintenance_mode' => 'boolean',
            'default_pickup_enabled' => 'boolean',
            'tax_percentage' => 'decimal:2',
            'shipping_volumetric_factor' => 'decimal:2',
            'default_fixed_shipping_price' => 'decimal:2',
            'pos_manual_discount_without_approval' => 'decimal:2',
            'pos_reservation_minutes' => 'integer',
            'cash_require_open_session' => 'boolean',
            'cash_allow_negative_difference' => 'boolean',
            'cash_max_manual_expense_without_admin' => 'decimal:2',
            'available_locales' => 'array',
            'google_login_enabled' => 'boolean',
            'google_client_secret' => 'encrypted',
        ];
    }

    public static function current(): self
    {
        return self::query()->firstOrCreate([
            'id' => 1,
        ], [
            'store_name' => 'Qué Económico',
            'currency' => 'CLP',
            'tax_percentage' => 19,
            'maintenance_mode' => false,
        ]);
    }
}
