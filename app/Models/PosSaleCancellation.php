<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosSaleCancellation extends Model
{
    protected $fillable = [
        'order_id', 'cash_register_session_id', 'reason', 'notes', 'cancelled_by',
        'cancelled_at', 'restore_stock',
    ];

    protected function casts(): array
    {
        return ['cancelled_at' => 'datetime', 'restore_stock' => 'boolean'];
    }

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function session(): BelongsTo { return $this->belongsTo(CashRegisterSession::class, 'cash_register_session_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class, 'cancelled_by'); }
}
