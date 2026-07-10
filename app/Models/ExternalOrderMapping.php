<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalOrderMapping extends Model
{
    protected $fillable = ['integration_id', 'order_id', 'external_order_id', 'external_order_number', 'external_status', 'import_status', 'sync_status', 'last_sync_at', 'metadata'];
    protected function casts(): array { return ['last_sync_at' => 'datetime', 'metadata' => 'array']; }
    public function integration(): BelongsTo { return $this->belongsTo(Integration::class); }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
}
