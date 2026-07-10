<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosCartItem extends Model
{
    protected $fillable = [
        'pos_cart_id', 'item_type', 'product_id', 'product_variant_id', 'product_pack_id', 'quantity',
        'regular_unit_price', 'final_unit_price', 'line_subtotal', 'line_discount', 'line_tax',
        'line_total', 'applied_rules', 'manual_discount_amount', 'manual_discount_reason',
        'stock_reservation_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer', 'regular_unit_price' => 'decimal:2', 'final_unit_price' => 'decimal:2',
            'line_subtotal' => 'decimal:2', 'line_discount' => 'decimal:2', 'line_tax' => 'decimal:2',
            'line_total' => 'decimal:2', 'manual_discount_amount' => 'decimal:2', 'applied_rules' => 'array',
        ];
    }

    public function cart(): BelongsTo { return $this->belongsTo(PosCart::class, 'pos_cart_id'); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function variant(): BelongsTo { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
    public function pack(): BelongsTo { return $this->belongsTo(ProductPack::class, 'product_pack_id'); }
    public function stockReservation(): BelongsTo { return $this->belongsTo(StockReservation::class); }
}
