<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckoutAddress extends Model
{
    protected $fillable = ['cart_session_id', 'address_type', 'customer_address_id', 'contact_name', 'phone', 'email', 'country', 'region', 'commune', 'city', 'street', 'number', 'apartment', 'postal_code', 'reference'];
    public function cart(): BelongsTo { return $this->belongsTo(CartSession::class, 'cart_session_id'); }
    public function customerAddress(): BelongsTo { return $this->belongsTo(CustomerAddress::class); }
}
