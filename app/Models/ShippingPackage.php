<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingPackage extends Model
{
    protected $fillable = ['order_id', 'cart_session_id', 'weight', 'height', 'width', 'length', 'volumetric_weight', 'billable_weight', 'package_count', 'notes'];
}
