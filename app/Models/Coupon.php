<?php

namespace App\Models;

use App\Models\Concerns\HasActiveWindow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasActiveWindow, SoftDeletes;

    protected $fillable = ['code', 'name', 'description', 'discount_type', 'discount_value', 'starts_at', 'ends_at', 'usage_limit', 'usage_count', 'usage_limit_per_customer', 'min_subtotal', 'min_quantity', 'is_active'];
    protected function casts(): array { return ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'is_active' => 'boolean']; }
    public function products(): BelongsToMany { return $this->belongsToMany(Product::class, 'coupon_products')->withPivot('product_variant_id')->withTimestamps(); }
    public function categories(): BelongsToMany { return $this->belongsToMany(Category::class, 'coupon_categories')->withTimestamps(); }
    public function usages(): HasMany { return $this->hasMany(CouponUsage::class); }
}
