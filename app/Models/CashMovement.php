<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashMovement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'cash_register_session_id', 'movement_type', 'amount', 'payment_method_id',
        'description', 'reference_type', 'reference_id', 'user_id', 'created_at',
    ];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'created_at' => 'datetime'];
    }

    public function session(): BelongsTo { return $this->belongsTo(CashRegisterSession::class, 'cash_register_session_id'); }
    public function paymentMethod(): BelongsTo { return $this->belongsTo(PosPaymentMethod::class, 'payment_method_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
