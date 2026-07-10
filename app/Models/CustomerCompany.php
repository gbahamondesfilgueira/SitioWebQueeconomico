<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerCompany extends Model
{
    protected $fillable = [
        'customer_profile_id',
        'company_name',
        'rut',
        'business_activity',
        'email',
        'phone',
        'website',
        'billing_email',
        'payment_terms',
        'credit_limit',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'credit_limit' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function customerProfile(): BelongsTo
    {
        return $this->belongsTo(CustomerProfile::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(CompanyContact::class, 'company_id');
    }
}
