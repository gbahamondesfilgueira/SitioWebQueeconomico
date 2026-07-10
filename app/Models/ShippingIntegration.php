<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingIntegration extends Model
{
    protected $fillable = ['shipping_carrier_id', 'integration_type', 'api_base_url', 'api_key', 'api_secret', 'account_number', 'sandbox_mode', 'is_active', 'metadata'];
    protected function casts(): array { return ['api_key' => 'encrypted', 'api_secret' => 'encrypted', 'sandbox_mode' => 'boolean', 'is_active' => 'boolean', 'metadata' => 'array']; }
    public function carrier(): BelongsTo { return $this->belongsTo(ShippingCarrier::class, 'shipping_carrier_id'); }
}
