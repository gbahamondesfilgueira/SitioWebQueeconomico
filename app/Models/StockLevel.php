<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockLevel extends Model
{
    use HasFactory;

    protected $fillable = ['warehouse_id', 'warehouse_location_id', 'product_id', 'product_variant_id', 'source_key', 'physical_stock', 'reserved_stock', 'minimum_stock', 'maximum_stock'];

    protected function casts(): array
    {
        return ['physical_stock' => 'integer', 'reserved_stock' => 'integer', 'minimum_stock' => 'integer', 'maximum_stock' => 'integer'];
    }

    public function getAvailableStockAttribute(): int
    {
        return max(0, (int) $this->physical_stock - (int) $this->reserved_stock);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'warehouse_location_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public static function sourceKey(int $warehouseId, ?int $locationId, int $productId, ?int $variantId): string
    {
        return implode(':', [$warehouseId, $locationId ?? 0, $productId, $variantId ?? 0]);
    }

    protected static function booted(): void
    {
        static::saving(function (StockLevel $level): void {
            $level->source_key = self::sourceKey(
                (int) $level->warehouse_id,
                $level->warehouse_location_id ? (int) $level->warehouse_location_id : null,
                (int) $level->product_id,
                $level->product_variant_id ? (int) $level->product_variant_id : null,
            );
        });
    }
}
