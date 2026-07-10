<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosReservationItem extends Model
{
    protected $fillable = ['pos_reservation_id', 'product_id', 'product_variant_id', 'quantity', 'stock_reservation_id'];

    protected function casts(): array
    {
        return ['quantity' => 'integer'];
    }

    public function reservation(): BelongsTo { return $this->belongsTo(PosReservation::class, 'pos_reservation_id'); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function variant(): BelongsTo { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
    public function stockReservation(): BelongsTo { return $this->belongsTo(StockReservation::class); }
}
