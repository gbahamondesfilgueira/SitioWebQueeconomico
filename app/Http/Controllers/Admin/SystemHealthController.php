<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditLogger;
use App\Services\SystemHealthService;

class SystemHealthController extends Controller
{
    public function __invoke(SystemHealthService $health)
    {
        AuditLogger::record('health_check', 'system', 'Health check ejecutado.');

        return view('admin.system.health', [
            'checks' => $health->run(),
            'latest' => $health->latest(),
        ]);
    }
}
