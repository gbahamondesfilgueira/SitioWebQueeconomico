<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosRefund extends Model
{
    protected $fillable = [
        'order_id', 'cash_register_session_id', 'refund_amount', 'reason',
        'refund_method', 'processed_by', 'processed_at', 'restore_stock', 'notes',
    ];

    protected function casts(): array
    {
        return ['refund_amount' => 'decimal:2', 'processed_at' => 'datetime', 'restore_stock' => 'boolean'];
    }

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function session(): BelongsTo { return $this->belongsTo(CashRegisterSession::class, 'cash_register_session_id'); }
    public function processor(): BelongsTo { return $this->belongsTo(User::class, 'processed_by'); }
}
