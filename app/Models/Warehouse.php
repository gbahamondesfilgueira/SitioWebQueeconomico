<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'code', 'type', 'address', 'city', 'region', 'commune', 'phone', 'email', 'is_active'];

    protected function casts(): array { return ['is_active' => 'boolean']; }
    public function locations(): HasMany { return $this->hasMany(WarehouseLocation::class); }
}
