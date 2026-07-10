<?php

namespace App\Models;

use App\Models\Concerns\HasActiveWindow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuantityDiscount extends Model
{
    use HasActiveWindow, SoftDeletes;

    protected $fillable = ['name', 'product_id', 'product_variant_id', 'category_id', 'brand_id', 'min_quantity', 'max_quantity', 'discount_type', 'discount_value', 'starts_at', 'ends_at', 'is_active'];
    protected function casts(): array { return ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'is_active' => 'boolean']; }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function variant(): BelongsTo { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function brand(): BelongsTo { return $this->belongsTo(Brand::class); }
}
