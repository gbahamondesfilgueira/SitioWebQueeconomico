<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashRegisterSession extends Model
{
    protected $fillable = [
        'pos_terminal_id', 'opened_by', 'closed_by', 'status', 'opening_amount',
        'expected_cash_amount', 'counted_cash_amount', 'cash_difference',
        'opened_at', 'closed_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'opening_amount' => 'decimal:2',
            'expected_cash_amount' => 'decimal:2',
            'counted_cash_amount' => 'decimal:2',
            'cash_difference' => 'decimal:2',
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function terminal(): BelongsTo { return $this->belongsTo(PosTerminal::class, 'pos_terminal_id'); }
    public function opener(): BelongsTo { return $this->belongsTo(User::class, 'opened_by'); }
    public function closer(): BelongsTo { return $this->belongsTo(User::class, 'closed_by'); }
    public function movements(): HasMany { return $this->hasMany(CashMovement::class); }
    public function orders(): HasMany { return $this->hasMany(Order::class); }
    public function cancellations(): HasMany { return $this->hasMany(PosSaleCancellation::class); }
    public function refunds(): HasMany { return $this->hasMany(PosRefund::class); }
}
