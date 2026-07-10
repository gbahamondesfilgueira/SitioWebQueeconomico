<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'legal_name', 'rut', 'email', 'phone', 'address', 'currency', 'tax_percentage', 'is_active'];
    protected function casts(): array { return ['tax_percentage' => 'decimal:2', 'is_active' => 'boolean']; }
}
