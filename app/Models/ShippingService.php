<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingService extends Model
{
    use SoftDeletes;
    protected $fillable = ['shipping_carrier_id', 'name', 'code', 'service_type', 'description', 'estimated_days_min', 'estimated_days_max', 'is_active'];
    protected function casts(): array { return ['is_active' => 'boolean']; }
    public function carrier(): BelongsTo { return $this->belongsTo(ShippingCarrier::class, 'shipping_carrier_id'); }
}
