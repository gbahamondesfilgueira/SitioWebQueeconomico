<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends Model
{
    protected $fillable = [
        'customer_profile_id',
        'address_type',
        'address_label',
        'contact_name',
        'phone',
        'country',
        'region',
        'province',
        'commune',
        'city',
        'street',
        'number',
        'apartment',
        'postal_code',
        'reference',
        'latitude',
        'longitude',
        'is_default',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function customerProfile(): BelongsTo
    {
        return $this->belongsTo(CustomerProfile::class);
    }
}
