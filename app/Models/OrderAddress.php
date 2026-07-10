<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderAddress extends Model
{
    protected $fillable = ['order_id', 'address_type', 'contact_name', 'phone', 'email', 'country', 'region', 'commune', 'city', 'street', 'number', 'apartment', 'postal_code', 'reference'];
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
}
