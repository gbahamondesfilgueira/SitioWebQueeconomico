<?php

namespace App\Models;

use App\Models\Concerns\HasActiveWindow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceListItem extends Model
{
    use HasActiveWindow;

    protected $fillable = ['price_list_id', 'product_id', 'product_variant_id', 'price', 'starts_at', 'ends_at', 'is_active'];
    protected function casts(): array { return ['price' => 'decimal:2', 'starts_at' => 'datetime', 'ends_at' => 'datetime', 'is_active' => 'boolean']; }
    public function priceList(): BelongsTo { return $this->belongsTo(PriceList::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function variant(): BelongsTo { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
}
