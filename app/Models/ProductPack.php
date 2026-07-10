<?php

namespace App\Models;

use App\Models\Concerns\HasActiveWindow;
use App\Models\Concerns\HasAutoSlug;
use App\Services\PricingService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPack extends Model
{
    use HasActiveWindow, HasAutoSlug, SoftDeletes;

    protected $fillable = ['name', 'slug', 'sku', 'barcode', 'description', 'regular_price', 'pack_price', 'starts_at', 'ends_at', 'is_active', 'is_visible', 'image_path'];
    protected function casts(): array { return ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'is_active' => 'boolean', 'is_visible' => 'boolean']; }
    public function items(): HasMany { return $this->hasMany(ProductPackItem::class); }
    public function getAvailableStock(?int $warehouseId = null): float { return app(PricingService::class)->calculatePackAvailableStock($this, $warehouseId); }
}
