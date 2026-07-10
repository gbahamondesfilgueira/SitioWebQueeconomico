<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiClient extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'code', 'description', 'token_hash', 'permissions', 'rate_limit_per_minute', 'allowed_ips', 'is_active', 'last_used_at', 'created_by'];
    protected function casts(): array { return ['permissions' => 'array', 'allowed_ips' => 'array', 'is_active' => 'boolean', 'last_used_at' => 'datetime']; }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
}
