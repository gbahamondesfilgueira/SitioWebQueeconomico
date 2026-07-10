<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Integration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'code', 'provider_type', 'provider_name', 'description', 'environment', 'status',
        'base_url', 'api_key', 'api_secret', 'access_token', 'refresh_token', 'token_expires_at',
        'webhook_secret', 'settings', 'last_sync_at', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'api_key' => 'encrypted',
            'api_secret' => 'encrypted',
            'access_token' => 'encrypted',
            'refresh_token' => 'encrypted',
            'webhook_secret' => 'encrypted',
            'settings' => 'array',
            'token_expires_at' => 'datetime',
            'last_sync_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function logs(): HasMany { return $this->hasMany(IntegrationLog::class); }
    public function productMappings(): HasMany { return $this->hasMany(ExternalProductMapping::class); }
    public function orderMappings(): HasMany { return $this->hasMany(ExternalOrderMapping::class); }
    public function customerMappings(): HasMany { return $this->hasMany(ExternalCustomerMapping::class); }
    public function shippingMappings(): HasMany { return $this->hasMany(ExternalShippingMapping::class); }
    public function webhookEvents(): HasMany { return $this->hasMany(WebhookEvent::class); }
    public function syncJobs(): HasMany { return $this->hasMany(SyncJob::class); }
}
