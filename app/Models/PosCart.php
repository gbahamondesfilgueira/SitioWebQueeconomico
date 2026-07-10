<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PosCart extends Model
{
    protected $fillable = [
        'pos_terminal_id', 'user_id', 'customer_profile_id', 'status', 'subtotal_regular', 'subtotal',
        'item_discount_total', 'coupon_discount_total', 'manual_discount_total', 'tax_total',
        'grand_total', 'currency', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal_regular' => 'decimal:2', 'subtotal' => 'decimal:2', 'item_discount_total' => 'decimal:2',
            'coupon_discount_total' => 'decimal:2', 'manual_discount_total' => 'decimal:2',
            'tax_total' => 'decimal:2', 'grand_total' => 'decimal:2',
        ];
    }

    public function terminal(): BelongsTo { return $this->belongsTo(PosTerminal::class, 'pos_terminal_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function customer(): BelongsTo { return $this->belongsTo(CustomerProfile::class, 'customer_profile_id'); }
    public function items(): HasMany { return $this->hasMany(PosCartItem::class); }
    public function coupons(): HasMany { return $this->hasMany(PosCartCoupon::class); }
    public function payments(): HasMany { return $this->hasMany(PosCartPayment::class); }
    public function discounts(): HasMany { return $this->hasMany(PosManualDiscount::class); }
}
