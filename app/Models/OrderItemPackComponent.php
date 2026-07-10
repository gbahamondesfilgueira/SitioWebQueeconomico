<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemPackComponent extends Model
{
    protected $fillable = ['order_item_id', 'product_id', 'product_variant_id', 'product_name', 'variant_name', 'sku', 'quantity_per_pack', 'total_quantity'];
    public function orderItem(): BelongsTo { return $this->belongsTo(OrderItem::class); }
}
