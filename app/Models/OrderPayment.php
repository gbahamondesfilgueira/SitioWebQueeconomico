<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPayment extends Model
{
    protected $fillable = ['order_id', 'payment_method', 'payment_label', 'payment_status', 'amount', 'transaction_id', 'authorization_code', 'paid_at', 'metadata'];
    protected function casts(): array { return ['amount' => 'decimal:2', 'paid_at' => 'datetime', 'metadata' => 'array']; }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
}
