<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosQuoteItem extends Model
{
    protected $fillable = [
        'pos_quote_id', 'item_type', 'product_id', 'product_variant_id', 'product_pack_id', 'quantity',
        'regular_unit_price', 'final_unit_price', 'line_total', 'applied_rules',
    ];

    protected function casts(): array
    {
        return ['quantity' => 'integer', 'regular_unit_price' => 'decimal:2', 'final_unit_price' => 'decimal:2', 'line_total' => 'decimal:2', 'applied_rules' => 'array'];
    }

    public function quote(): BelongsTo { return $this->belongsTo(PosQuote::class, 'pos_quote_id'); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function variant(): BelongsTo { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
    public function pack(): BelongsTo { return $this->belongsTo(ProductPack::class, 'product_pack_id'); }
}
