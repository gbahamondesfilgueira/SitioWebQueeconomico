<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckoutShippingMethod extends Model
{
    protected $fillable = ['cart_session_id', 'shipping_quote_id', 'carrier_name', 'service_name', 'shipping_type', 'estimated_price', 'estimated_days', 'estimated_days_min', 'estimated_days_max', 'status', 'metadata'];

    protected function casts(): array
    {
        return ['metadata' => 'array', 'estimated_price' => 'decimal:2'];
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(CartSession::class, 'cart_session_id');
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(ShippingQuote::class, 'shipping_quote_id');
    }
}
