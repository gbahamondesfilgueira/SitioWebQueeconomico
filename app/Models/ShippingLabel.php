<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingLabel extends Model
{
    protected $fillable = ['order_id', 'shipping_carrier_id', 'label_number', 'tracking_number', 'label_file_path', 'status', 'generated_by', 'generated_at', 'printed_at', 'metadata'];
    protected function casts(): array { return ['metadata' => 'array', 'generated_at' => 'datetime', 'printed_at' => 'datetime']; }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function carrier(): BelongsTo { return $this->belongsTo(ShippingCarrier::class, 'shipping_carrier_id'); }
}
