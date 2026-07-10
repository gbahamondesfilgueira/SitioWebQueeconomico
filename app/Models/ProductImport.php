<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImport extends Model
{
    protected $fillable = [
        'import_type', 'file_path', 'status', 'total_rows', 'successful_rows',
        'failed_rows', 'summary', 'errors', 'created_by', 'started_at', 'finished_at',
    ];

    protected function casts(): array
    {
        return ['summary' => 'array', 'errors' => 'array', 'started_at' => 'datetime', 'finished_at' => 'datetime'];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
