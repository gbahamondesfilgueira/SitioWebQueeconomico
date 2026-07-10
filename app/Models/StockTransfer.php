<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockTransfer extends Model
{
    protected $fillable = ['origin_warehouse_id', 'origin_location_id', 'destination_warehouse_id', 'destination_location_id', 'status', 'notes', 'created_by', 'sent_by', 'sent_at', 'received_by', 'received_at'];
    protected function casts(): array { return ['sent_at' => 'datetime', 'received_at' => 'datetime']; }
    public function items(): HasMany { return $this->hasMany(StockTransferItem::class); }
    public function originWarehouse(): BelongsTo { return $this->belongsTo(Warehouse::class, 'origin_warehouse_id'); }
    public function destinationWarehouse(): BelongsTo { return $this->belongsTo(Warehouse::class, 'destination_warehouse_id'); }
    public function originLocation(): BelongsTo { return $this->belongsTo(WarehouseLocation::class, 'origin_location_id'); }
    public function destinationLocation(): BelongsTo { return $this->belongsTo(WarehouseLocation::class, 'destination_location_id'); }
}
