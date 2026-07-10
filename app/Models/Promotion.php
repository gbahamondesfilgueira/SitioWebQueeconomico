<?php

namespace App\Models;

use App\Models\Concerns\HasActiveWindow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use HasActiveWindow, SoftDeletes;

    public const TYPES = ['percentage_discount', 'fixed_discount', 'fixed_price', 'quantity_discount', 'buy_x_get_y', 'free_shipping', 'gift_product', 'bundle_discount'];

    protected $fillable = [
        'name', 'code', 'description', 'promotion_type', 'starts_at', 'ends_at', 'priority', 'is_stackable', 'is_active',
        'usage_limit', 'usage_count', 'min_subtotal', 'min_quantity', 'customer_role',
        'discount_percentage', 'discount_amount', 'fixed_price', 'buy_quantity', 'get_quantity', 'gift_product_id', 'gift_product_variant_id',
    ];
    protected function casts(): array
    {
        return ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'is_stackable' => 'boolean', 'is_active' => 'boolean'];
    }
    public function products(): BelongsToMany { return $this->belongsToMany(Product::class, 'promotion_products')->withPivot('product_variant_id')->withTimestamps(); }
    public function categories(): BelongsToMany { return $this->belongsToMany(Category::class, 'promotion_categories')->withTimestamps(); }
    public function brands(): BelongsToMany { return $this->belongsToMany(Brand::class, 'promotion_brands')->withTimestamps(); }
    public function tags(): BelongsToMany { return $this->belongsToMany(Tag::class, 'promotion_tags')->withTimestamps(); }
}
