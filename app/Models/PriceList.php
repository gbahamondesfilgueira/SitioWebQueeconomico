<?php

namespace App\Models;

use App\Models\Concerns\HasActiveWindow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceList extends Model
{
    use HasActiveWindow, SoftDeletes;

    protected $fillable = ['name', 'code', 'description', 'currency', 'starts_at', 'ends_at', 'is_default', 'is_active'];
    protected function casts(): array { return ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'is_default' => 'boolean', 'is_active' => 'boolean']; }
    public function items(): HasMany { return $this->hasMany(PriceListItem::class); }
}
