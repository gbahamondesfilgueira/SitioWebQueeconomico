<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'item_type', 'product_id', 'product_variant_id', 'product_pack_id', 'product_name', 'variant_name', 'sku', 'quantity', 'regular_unit_price', 'final_unit_price', 'line_subtotal', 'line_discount', 'line_tax', 'line_total', 'applied_rules', 'metadata'];
    protected function casts(): array { return ['applied_rules' => 'array', 'metadata' => 'array', 'regular_unit_price' => 'decimal:2', 'final_unit_price' => 'decimal:2', 'line_subtotal' => 'decimal:2', 'line_discount' => 'decimal:2', 'line_tax' => 'decimal:2', 'line_total' => 'decimal:2']; }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function variant(): BelongsTo { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
    public function pack(): BelongsTo { return $this->belongsTo(ProductPack::class, 'product_pack_id'); }
    public function packComponents(): HasMany { return $this->hasMany(OrderItemPackComponent::class); }
}
