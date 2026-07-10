<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderReturn extends Model
{
    protected $fillable = ['order_id', 'return_number', 'status', 'reason', 'notes', 'requested_by', 'approved_by', 'approved_at', 'received_at', 'refunded_at'];
    protected function casts(): array { return ['approved_at' => 'datetime', 'received_at' => 'datetime', 'refunded_at' => 'datetime']; }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function items(): HasMany { return $this->hasMany(OrderReturnItem::class); }
}
