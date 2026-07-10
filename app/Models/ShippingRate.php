<?php

namespace App\Models;

use App\Models\Concerns\HasActiveWindow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingRate extends Model
{
    use HasActiveWindow, SoftDeletes;
    protected $fillable = ['shipping_carrier_id', 'shipping_service_id', 'shipping_zone_id', 'min_weight', 'max_weight', 'max_height', 'max_width', 'max_length', 'max_volume', 'price', 'currency', 'starts_at', 'ends_at', 'is_active'];
    protected function casts(): array { return ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'is_active' => 'boolean', 'price' => 'decimal:2']; }
    public function carrier(): BelongsTo { return $this->belongsTo(ShippingCarrier::class, 'shipping_carrier_id'); }
    public function service(): BelongsTo { return $this->belongsTo(ShippingService::class, 'shipping_service_id'); }
    public function zone(): BelongsTo { return $this->belongsTo(ShippingZone::class, 'shipping_zone_id'); }
}
