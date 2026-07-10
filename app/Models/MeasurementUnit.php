<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeasurementUnit extends Model
{
    public const TYPES = ['weight', 'dimension', 'quantity', 'volume', 'unit'];

    protected $fillable = [
        'name',
        'code',
        'type',
    ];
}
