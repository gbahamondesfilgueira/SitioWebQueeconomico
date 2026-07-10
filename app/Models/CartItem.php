<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_session_id', 'item_type', 'product_id', 'product_variant_id', 'product_pack_id',
        'quantity', 'unit_price', 'regular_price', 'discount_amount', 'final_unit_price',
        'line_subtotal', 'line_discount', 'line_total', 'applied_rules', 'stock_reservation_id',
    ];

    protected function casts(): array
    {
        return [
            'applied_rules' => 'array',
            'unit_price' => 'decimal:2',
            'regular_price' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'final_unit_price' => 'decimal:2',
            'line_subtotal' => 'decimal:2',
            'line_discount' => 'decimal:2',
            'line_total' => 'decimal:2',
        ];
    }

    public function cart(): BelongsTo { return $this->belongsTo(CartSession::class, 'cart_session_id'); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function variant(): BelongsTo { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
    public function pack(): BelongsTo { return $this->belongsTo(ProductPack::class, 'product_pack_id'); }
    public function reservation(): BelongsTo { return $this->belongsTo(StockReservation::class, 'stock_reservation_id'); }
}
