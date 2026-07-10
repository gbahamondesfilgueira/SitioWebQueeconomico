<?php

namespace App\Models;

use App\Models\Concerns\HasAutoSlug;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasAutoSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo_path',
        'website',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
