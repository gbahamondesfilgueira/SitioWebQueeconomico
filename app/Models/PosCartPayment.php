<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosCartPayment extends Model
{
    protected $fillable = ['pos_cart_id', 'payment_method_id', 'amount', 'reference', 'notes'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2'];
    }

    public function cart(): BelongsTo { return $this->belongsTo(PosCart::class, 'pos_cart_id'); }
    public function method(): BelongsTo { return $this->belongsTo(PosPaymentMethod::class, 'payment_method_id'); }
}
