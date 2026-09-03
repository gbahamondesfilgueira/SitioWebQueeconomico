<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CartSession extends Model
{
    protected $fillable = ['user_id', 'session_id', 'status', 'destination_region_code', 'expires_at'];

    protected function casts(): array
    {
        return ['expires_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(CartCoupon::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(CheckoutAddress::class);
    }

    public function shippingMethod(): HasOne
    {
        return $this->hasOne(CheckoutShippingMethod::class);
    }

    public function paymentMethod(): HasOne
    {
        return $this->hasOne(CheckoutPaymentMethod::class);
    }
}
