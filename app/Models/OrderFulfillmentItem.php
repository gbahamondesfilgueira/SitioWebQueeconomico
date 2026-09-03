<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderFulfillmentItem extends Model
{
    protected $fillable = ['order_fulfillment_id', 'order_item_id', 'product_id', 'product_variant_id', 'warehouse_location_id', 'stock_reservation_id', 'required_quantity', 'picked_quantity', 'packed_quantity', 'status'];

    protected function casts(): array
    {
        return ['required_quantity' => 'decimal:3', 'picked_quantity' => 'decimal:3', 'packed_quantity' => 'decimal:3'];
    }

    public function fulfillment(): BelongsTo
    {
        return $this->belongsTo(OrderFulfillment::class, 'order_fulfillment_id');
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'warehouse_location_id');
    }

    public function stockReservation(): BelongsTo
    {
        return $this->belongsTo(StockReservation::class);
    }
}
