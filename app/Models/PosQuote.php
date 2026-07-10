<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PosQuote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'quote_number', 'pos_terminal_id', 'user_id', 'customer_profile_id', 'status',
        'subtotal', 'discount_total', 'tax_total', 'grand_total', 'expires_at', 'notes',
    ];

    protected function casts(): array
    {
        return ['subtotal' => 'decimal:2', 'discount_total' => 'decimal:2', 'tax_total' => 'decimal:2', 'grand_total' => 'decimal:2', 'expires_at' => 'datetime'];
    }

    public function terminal(): BelongsTo { return $this->belongsTo(PosTerminal::class, 'pos_terminal_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function customer(): BelongsTo { return $this->belongsTo(CustomerProfile::class, 'customer_profile_id'); }
    public function items(): HasMany { return $this->hasMany(PosQuoteItem::class); }
}
