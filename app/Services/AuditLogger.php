<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public static function record(string $action, string $module, ?string $description = null, ?Request $request = null, ?int $userId = null): void
    {
        $request ??= request();

        AuditLog::query()->create([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
