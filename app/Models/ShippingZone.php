<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingZone extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'country', 'region', 'commune', 'city', 'postal_code', 'is_active'];
    protected function casts(): array { return ['is_active' => 'boolean']; }
}
