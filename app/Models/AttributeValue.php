<?php

namespace App\Models;

use App\Models\Concerns\HasAutoSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttributeValue extends Model
{
    use HasAutoSlug;

    protected $fillable = ['attribute_id', 'value', 'slug', 'color_hex', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function getNameAttribute(): string
    {
        return $this->value;
    }

    public function slugSourceValue(): string
    {
        return $this->value;
    }
}
