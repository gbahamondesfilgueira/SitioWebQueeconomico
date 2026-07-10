<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['name', 'code', 'symbol', 'decimal_places', 'is_default', 'is_active'];
    protected function casts(): array { return ['decimal_places' => 'integer', 'is_default' => 'boolean', 'is_active' => 'boolean']; }
}
