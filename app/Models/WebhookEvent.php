<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebhookEvent extends Model
{
    public $timestamps = false;

    protected $fillable = ['integration_id', 'event_source', 'event_type', 'external_event_id', 'payload', 'headers', 'signature', 'status', 'error_message', 'processed_at', 'created_at'];
    protected function casts(): array { return ['payload' => 'array', 'headers' => 'array', 'processed_at' => 'datetime', 'created_at' => 'datetime']; }
    public function integration(): BelongsTo { return $this->belongsTo(Integration::class); }
}
