<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number', 'user_id', 'customer_profile_id', 'cart_session_id', 'order_channel',
        'pos_terminal_id', 'cash_register_session_id', 'sold_by', 'source_reference', 'order_status', 'payment_status',
        'fulfillment_status', 'subtotal_regular', 'subtotal', 'item_discount_total', 'coupon_discount_total',
        'shipping_total', 'tax_total', 'grand_total', 'currency', 'customer_email', 'customer_phone',
        'customer_name', 'customer_rut', 'notes', 'internal_notes', 'confirmed_at', 'paid_at',
        'cancelled_at', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal_regular' => 'decimal:2', 'subtotal' => 'decimal:2', 'item_discount_total' => 'decimal:2',
            'coupon_discount_total' => 'decimal:2', 'shipping_total' => 'decimal:2', 'tax_total' => 'decimal:2',
            'grand_total' => 'decimal:2', 'confirmed_at' => 'datetime', 'paid_at' => 'datetime',
            'cancelled_at' => 'datetime', 'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function seller(): BelongsTo { return $this->belongsTo(User::class, 'sold_by'); }
    public function customerProfile(): BelongsTo { return $this->belongsTo(CustomerProfile::class); }
    public function cart(): BelongsTo { return $this->belongsTo(CartSession::class, 'cart_session_id'); }
    public function posTerminal(): BelongsTo { return $this->belongsTo(PosTerminal::class); }
    public function cashRegisterSession(): BelongsTo { return $this->belongsTo(CashRegisterSession::class); }
    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
    public function addresses(): HasMany { return $this->hasMany(OrderAddress::class); }
    public function payments(): HasMany { return $this->hasMany(OrderPayment::class); }
    public function shipment(): HasOne { return $this->hasOne(OrderShipment::class); }
    public function histories(): HasMany { return $this->hasMany(OrderStatusHistory::class); }
    public function fulfillment(): HasOne { return $this->hasOne(OrderFulfillment::class); }
    public function cancellations(): HasMany { return $this->hasMany(OrderCancellation::class); }
    public function returns(): HasMany { return $this->hasMany(OrderReturn::class); }
    public function posCancellation(): HasOne { return $this->hasOne(PosSaleCancellation::class); }
    public function posRefunds(): HasMany { return $this->hasMany(PosRefund::class); }
}
