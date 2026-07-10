<?php

namespace App\Models;

use App\Models\Concerns\HasProductPricingAndWeight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasProductPricingAndWeight, SoftDeletes;

    protected $fillable = [
        'product_id', 'sku', 'barcode', 'name',
        'cost_price', 'regular_price', 'sale_price', 'sale_starts_at', 'sale_ends_at',
        'weight', 'height', 'width', 'length', 'weight_unit_id', 'dimension_unit_id',
        'image_path', 'is_active',
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
        ];
    }

    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function weightUnit(): BelongsTo { return $this->belongsTo(MeasurementUnit::class, 'weight_unit_id'); }
    public function dimensionUnit(): BelongsTo { return $this->belongsTo(MeasurementUnit::class, 'dimension_unit_id'); }
    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'product_variant_attribute_values')
            ->withPivot('attribute_id')
            ->withTimestamps();
    }
}
