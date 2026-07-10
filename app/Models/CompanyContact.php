<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyContact extends Model
{
    protected $fillable = ['company_id', 'name', 'position', 'email', 'phone', 'mobile', 'is_primary'];

    protected function casts(): array
    {
        return ['is_primary' => 'boolean'];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(CustomerCompany::class, 'company_id');
    }
}
