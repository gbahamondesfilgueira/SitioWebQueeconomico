<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockLevel extends Model
{
    protected $fillable = ['warehouse_id', 'warehouse_location_id', 'product_id', 'product_variant_id', 'physical_stock', 'reserved_stock', 'minimum_stock', 'maximum_stock'];
    protected function casts(): array { return ['physical_stock' => 'integer', 'reserved_stock' => 'integer', 'minimum_stock' => 'integer', 'maximum_stock' => 'integer']; }
    public function getAvailableStockAttribute(): int { return max(0, (int) $this->physical_stock - (int) $this->reserved_stock); }
    public function warehouse(): BelongsTo { return $this->belongsTo(Warehouse::class); }
    public function location(): BelongsTo { return $this->belongsTo(WarehouseLocation::class, 'warehouse_location_id'); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function variant(): BelongsTo { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
}
