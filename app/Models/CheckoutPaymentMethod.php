<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckoutPaymentMethod extends Model
{
    protected $fillable = ['cart_session_id', 'payment_method', 'payment_label', 'status', 'metadata'];
    protected function casts(): array { return ['metadata' => 'array']; }
    public function cart(): BelongsTo { return $this->belongsTo(CartSession::class, 'cart_session_id'); }
}
