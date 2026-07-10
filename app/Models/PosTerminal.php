<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PosTerminal extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'code', 'warehouse_id', 'location_id', 'description', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function warehouse(): BelongsTo { return $this->belongsTo(Warehouse::class); }
    public function location(): BelongsTo { return $this->belongsTo(WarehouseLocation::class, 'location_id'); }
    public function carts(): HasMany { return $this->hasMany(PosCart::class); }
    public function quotes(): HasMany { return $this->hasMany(PosQuote::class); }
    public function reservations(): HasMany { return $this->hasMany(PosReservation::class); }
    public function cashRegisterSessions(): HasMany { return $this->hasMany(CashRegisterSession::class); }
}
