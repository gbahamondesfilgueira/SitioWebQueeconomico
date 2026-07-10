<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingTrackingEvent extends Model
{
    protected $fillable = ['order_shipment_id', 'status', 'description', 'location', 'event_at', 'metadata'];
    protected function casts(): array { return ['metadata' => 'array', 'event_at' => 'datetime']; }
    public function shipment(): BelongsTo { return $this->belongsTo(OrderShipment::class, 'order_shipment_id'); }
}
