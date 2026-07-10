<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingQuote extends Model
{
    protected $fillable = ['cart_session_id', 'order_id', 'shipping_carrier_id', 'shipping_service_id', 'shipping_rate_id', 'destination_country', 'destination_region', 'destination_commune', 'destination_city', 'physical_weight', 'volumetric_weight', 'billable_weight', 'price', 'currency', 'estimated_days_min', 'estimated_days_max', 'expires_at', 'metadata'];
    protected function casts(): array { return ['metadata' => 'array', 'expires_at' => 'datetime', 'price' => 'decimal:2']; }
    public function carrier(): BelongsTo { return $this->belongsTo(ShippingCarrier::class, 'shipping_carrier_id'); }
    public function service(): BelongsTo { return $this->belongsTo(ShippingService::class, 'shipping_service_id'); }
    public function rate(): BelongsTo { return $this->belongsTo(ShippingRate::class, 'shipping_rate_id'); }
}
