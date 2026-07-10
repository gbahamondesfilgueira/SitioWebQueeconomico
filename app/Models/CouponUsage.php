<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    protected $fillable = ['coupon_id', 'user_id', 'order_id', 'used_at', 'discount_amount'];
    protected function casts(): array { return ['used_at' => 'datetime']; }
}
