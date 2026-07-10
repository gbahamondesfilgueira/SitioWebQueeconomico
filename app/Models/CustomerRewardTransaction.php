<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerRewardTransaction extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['customer_profile_id', 'type', 'points', 'description', 'reference_type', 'reference_id'];

    protected function casts(): array
    {
        return ['points' => 'decimal:2'];
    }

    public function customerProfile(): BelongsTo
    {
        return $this->belongsTo(CustomerProfile::class);
    }
}
