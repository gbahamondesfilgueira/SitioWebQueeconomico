<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderShipment extends Model
{
    protected $fillable = ['order_id', 'shipping_carrier_id', 'shipping_service_id', 'shipping_quote_id', 'carrier_name', 'service_name', 'shipping_type', 'shipping_status', 'tracking_number', 'tracking_url', 'shipping_cost', 'estimated_days', 'estimated_days_min', 'estimated_days_max', 'shipped_at', 'delivered_at', 'metadata'];

    protected function casts(): array
    {
        return ['shipping_cost' => 'decimal:2', 'shipped_at' => 'datetime', 'delivered_at' => 'datetime', 'metadata' => 'array'];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(ShippingCarrier::class, 'shipping_carrier_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ShippingService::class, 'shipping_service_id');
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(ShippingQuote::class, 'shipping_quote_id');
    }

    public function trackingEvents(): HasMany
    {
        return $this->hasMany(ShippingTrackingEvent::class);
    }
}
