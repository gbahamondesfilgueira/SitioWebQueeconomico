<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemBackup extends Model
{
    protected $fillable = ['backup_type', 'status', 'file_path', 'file_size', 'started_at', 'finished_at', 'error_message', 'created_by'];
    protected function casts(): array { return ['started_at' => 'datetime', 'finished_at' => 'datetime']; }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
}
