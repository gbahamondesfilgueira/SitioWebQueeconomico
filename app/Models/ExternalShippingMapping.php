<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalShippingMapping extends Model
{
    protected $fillable = ['integration_id', 'order_shipment_id', 'shipping_carrier_id', 'external_shipment_id', 'external_label_id', 'external_tracking_number', 'external_status', 'last_sync_at', 'metadata'];
    protected function casts(): array { return ['last_sync_at' => 'datetime', 'metadata' => 'array']; }
    public function integration(): BelongsTo { return $this->belongsTo(Integration::class); }
    public function shipment(): BelongsTo { return $this->belongsTo(OrderShipment::class, 'order_shipment_id'); }
    public function carrier(): BelongsTo { return $this->belongsTo(ShippingCarrier::class, 'shipping_carrier_id'); }
}
