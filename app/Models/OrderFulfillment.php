<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderFulfillment extends Model
{
    protected $fillable = ['order_id', 'warehouse_id', 'status', 'assigned_to', 'started_at', 'completed_at', 'notes'];
    protected function casts(): array { return ['started_at' => 'datetime', 'completed_at' => 'datetime']; }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function warehouse(): BelongsTo { return $this->belongsTo(Warehouse::class); }
    public function assignedUser(): BelongsTo { return $this->belongsTo(User::class, 'assigned_to'); }
    public function items(): HasMany { return $this->hasMany(OrderFulfillmentItem::class); }
}
