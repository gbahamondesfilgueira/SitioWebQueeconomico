<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'code', 'type', 'address', 'city', 'region', 'commune', 'phone', 'email', 'is_active', 'is_central', 'fulfillment_priority'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'is_central' => 'boolean', 'fulfillment_priority' => 'integer'];
    }

    public function locations(): HasMany
    {
        return $this->hasMany(WarehouseLocation::class);
    }

    public function stockLevels(): HasMany
    {
        return $this->hasMany(StockLevel::class);
    }
}
