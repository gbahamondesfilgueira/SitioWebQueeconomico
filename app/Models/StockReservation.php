<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockReservation extends Model
{
    protected $fillable = ['warehouse_id', 'warehouse_location_id', 'product_id', 'product_variant_id', 'quantity', 'reference_type', 'reference_id', 'expires_at', 'status', 'user_id'];
    protected function casts(): array { return ['quantity' => 'integer', 'expires_at' => 'datetime']; }
    public function warehouse(): BelongsTo { return $this->belongsTo(Warehouse::class); }
    public function location(): BelongsTo { return $this->belongsTo(WarehouseLocation::class, 'warehouse_location_id'); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function variant(): BelongsTo { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
}
