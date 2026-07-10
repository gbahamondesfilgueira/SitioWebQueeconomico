<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    public const UPDATED_AT = null;
    protected $fillable = ['warehouse_id', 'warehouse_location_id', 'product_id', 'product_variant_id', 'movement_type', 'quantity', 'previous_stock', 'new_stock', 'reference_type', 'reference_id', 'notes', 'user_id'];
    public function warehouse(): BelongsTo { return $this->belongsTo(Warehouse::class); }
    public function location(): BelongsTo { return $this->belongsTo(WarehouseLocation::class, 'warehouse_location_id'); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function variant(): BelongsTo { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
