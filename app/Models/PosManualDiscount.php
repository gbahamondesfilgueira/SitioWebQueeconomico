<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosManualDiscount extends Model
{
    protected $fillable = [
        'pos_cart_id', 'pos_cart_item_id', 'discount_type', 'discount_value', 'discount_amount',
        'reason', 'requested_by', 'approved_by', 'approved_at', 'status',
    ];

    protected function casts(): array
    {
        return ['discount_value' => 'decimal:2', 'discount_amount' => 'decimal:2', 'approved_at' => 'datetime'];
    }

    public function cart(): BelongsTo { return $this->belongsTo(PosCart::class, 'pos_cart_id'); }
    public function item(): BelongsTo { return $this->belongsTo(PosCartItem::class, 'pos_cart_item_id'); }
    public function requester(): BelongsTo { return $this->belongsTo(User::class, 'requested_by'); }
    public function approver(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); }
}
