<?php

namespace App\Models;

use App\Models\Concerns\HasAutoSlug;
use App\Models\Concerns\HasProductPricingAndWeight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, HasAutoSlug, HasProductPricingAndWeight, SoftDeletes;

    protected $fillable = [
        'category_id', 'brand_id', 'supplier_id', 'tax_id', 'origin_country_id',
        'name', 'slug', 'sku', 'barcode', 'product_type',
        'short_description', 'long_description', 'technical_description',
        'cost_price', 'regular_price', 'sale_price', 'sale_starts_at', 'sale_ends_at',
        'weight', 'height', 'width', 'length', 'weight_unit_id', 'dimension_unit_id',
        'is_active', 'is_featured', 'is_visible',
        'seo_title', 'seo_description', 'seo_keywords',
    ];

    protected function casts(): array
    {
        return [
            'sale_starts_at' => 'datetime',
            'sale_ends_at' => 'datetime',
            'cost_price' => 'decimal:2',
            'regular_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'weight' => 'decimal:3',
            'height' => 'decimal:3',
            'width' => 'decimal:3',
            'length' => 'decimal:3',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_visible' => 'boolean',
        ];
    }

    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function brand(): BelongsTo { return $this->belongsTo(Brand::class); }
    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    public function tax(): BelongsTo { return $this->belongsTo(Tax::class); }
    public function originCountry(): BelongsTo { return $this->belongsTo(OriginCountry::class); }
    public function weightUnit(): BelongsTo { return $this->belongsTo(MeasurementUnit::class, 'weight_unit_id'); }
    public function dimensionUnit(): BelongsTo { return $this->belongsTo(MeasurementUnit::class, 'dimension_unit_id'); }
    public function variants(): HasMany { return $this->hasMany(ProductVariant::class); }
    public function images(): HasMany { return $this->hasMany(ProductImage::class)->orderByDesc('is_primary')->orderBy('sort_order'); }
    public function primaryImage(): HasMany { return $this->hasMany(ProductImage::class)->where('is_primary', true); }
    public function tags(): BelongsToMany { return $this->belongsToMany(Tag::class); }
    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'related_products', 'product_id', 'related_product_id')
            ->withPivot('relation_type')
            ->withTimestamps();
    }
}
