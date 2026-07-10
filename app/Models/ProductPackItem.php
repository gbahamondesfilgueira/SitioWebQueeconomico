<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPackItem extends Model
{
    protected $fillable = ['product_pack_id', 'product_id', 'product_variant_id', 'quantity'];
    public function pack(): BelongsTo { return $this->belongsTo(ProductPack::class, 'product_pack_id'); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function variant(): BelongsTo { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
}
