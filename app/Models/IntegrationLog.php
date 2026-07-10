<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntegrationLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'integration_id', 'direction', 'event_type', 'status', 'method', 'endpoint',
        'request_payload', 'response_payload', 'http_status', 'error_message',
        'attempts', 'reference_type', 'reference_id', 'external_reference',
        'started_at', 'finished_at', 'created_at',
    ];

    protected function casts(): array
    {
        return [
            'request_payload' => 'array',
            'response_payload' => 'array',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function integration(): BelongsTo { return $this->belongsTo(Integration::class); }
}
