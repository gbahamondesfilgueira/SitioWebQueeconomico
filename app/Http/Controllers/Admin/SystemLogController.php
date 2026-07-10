<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\IntegrationLog;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\File;

class SystemLogController extends Controller
{
    public function __invoke()
    {
        AuditLogger::record('viewed', 'system', 'Logs consultados.');

        $logFile = storage_path('logs/laravel.log');

        return view('admin.system.logs', [
            'laravelLogs' => File::exists($logFile) ? collect(explode("\n", File::get($logFile)))->take(-80)->values() : collect(),
            'auditLogs' => AuditLog::query()->with('user')->latest()->limit(20)->get(),
            'integrationLogs' => IntegrationLog::query()->with('integration')->where('status', 'failed')->latest('created_at')->limit(20)->get(),
        ]);
    }
}
