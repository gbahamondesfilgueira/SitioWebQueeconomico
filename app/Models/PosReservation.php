<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PosReservation extends Model
{
    protected $fillable = ['reservation_number', 'pos_terminal_id', 'user_id', 'customer_profile_id', 'status', 'expires_at', 'notes'];

    protected function casts(): array
    {
        return ['expires_at' => 'datetime'];
    }

    public function terminal(): BelongsTo { return $this->belongsTo(PosTerminal::class, 'pos_terminal_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function customer(): BelongsTo { return $this->belongsTo(CustomerProfile::class, 'customer_profile_id'); }
    public function items(): HasMany { return $this->hasMany(PosReservationItem::class); }
}
