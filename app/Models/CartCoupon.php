<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartCoupon extends Model
{
    protected $fillable = ['cart_session_id', 'coupon_id', 'coupon_code', 'discount_amount', 'applied_at'];
    protected function casts(): array { return ['discount_amount' => 'decimal:2', 'applied_at' => 'datetime']; }
    public function cart(): BelongsTo { return $this->belongsTo(CartSession::class, 'cart_session_id'); }
    public function coupon(): BelongsTo { return $this->belongsTo(Coupon::class); }
}
