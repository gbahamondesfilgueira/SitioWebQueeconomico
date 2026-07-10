<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderCancellation extends Model
{
    protected $fillable = ['order_id', 'reason', 'notes', 'cancelled_by', 'cancelled_at', 'restore_stock'];
    protected function casts(): array { return ['cancelled_at' => 'datetime', 'restore_stock' => 'boolean']; }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function cancelledBy(): BelongsTo { return $this->belongsTo(User::class, 'cancelled_by'); }
}
