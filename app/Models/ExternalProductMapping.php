<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalProductMapping extends Model
{
    protected $fillable = [
        'integration_id', 'product_id', 'product_variant_id', 'external_product_id', 'external_variant_id',
        'external_sku', 'external_barcode', 'external_url', 'sync_stock', 'sync_price', 'sync_images',
        'sync_description', 'last_stock_sync_at', 'last_price_sync_at', 'last_full_sync_at', 'metadata',
    ];

    protected function casts(): array
    {
        return [
            'sync_stock' => 'boolean', 'sync_price' => 'boolean', 'sync_images' => 'boolean',
            'sync_description' => 'boolean', 'last_stock_sync_at' => 'datetime',
            'last_price_sync_at' => 'datetime', 'last_full_sync_at' => 'datetime', 'metadata' => 'array',
        ];
    }

    public function integration(): BelongsTo { return $this->belongsTo(Integration::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function variant(): BelongsTo { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
}
