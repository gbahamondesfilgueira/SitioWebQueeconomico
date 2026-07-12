<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'code', 'type', 'address', 'city', 'region', 'commune', 'phone', 'email', 'is_active'];

    protected function casts(): array { return ['is_active' => 'boolean']; }
    public function locations(): HasMany { return $this->hasMany(WarehouseLocation::class); }
}
