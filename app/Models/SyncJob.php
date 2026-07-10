<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncJob extends Model
{
    protected $fillable = ['integration_id', 'job_type', 'status', 'priority', 'payload', 'attempts', 'max_attempts', 'scheduled_at', 'started_at', 'finished_at', 'error_message', 'created_by'];
    protected function casts(): array { return ['payload' => 'array', 'scheduled_at' => 'datetime', 'started_at' => 'datetime', 'finished_at' => 'datetime']; }
    public function integration(): BelongsTo { return $this->belongsTo(Integration::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
}
