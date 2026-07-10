<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OriginCountry extends Model
{
    protected $fillable = [
        'name',
        'iso_code',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
