<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemHealthCheck extends Model
{
    public $timestamps = false;

    protected $fillable = ['check_name', 'status', 'message', 'metadata', 'checked_at'];
    protected function casts(): array { return ['metadata' => 'array', 'checked_at' => 'datetime']; }
}
