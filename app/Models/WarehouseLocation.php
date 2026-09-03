<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseLocation extends Model
{
    use SoftDeletes;

    protected $fillable = ['warehouse_id', 'name', 'code', 'description', 'is_active', 'is_sellable'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'is_sellable' => 'boolean'];
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
