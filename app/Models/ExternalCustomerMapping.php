<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalCustomerMapping extends Model
{
    protected $fillable = ['integration_id', 'customer_profile_id', 'external_customer_id', 'external_email', 'external_phone', 'external_rut', 'metadata', 'last_sync_at'];
    protected function casts(): array { return ['metadata' => 'array', 'last_sync_at' => 'datetime']; }
    public function integration(): BelongsTo { return $this->belongsTo(Integration::class); }
    public function customer(): BelongsTo { return $this->belongsTo(CustomerProfile::class, 'customer_profile_id'); }
}
