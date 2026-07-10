<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\IntegrationLog;
use Illuminate\Console\Command;

class SystemCleanupLogs extends Command
{
    protected $signature = 'system:cleanup-logs {--days=90}';
    protected $description = 'Limpia logs antiguos no críticos.';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $audit = AuditLog::query()->where('created_at', '<', now()->subDays($days))->delete();
        $integration = IntegrationLog::query()->where('created_at', '<', now()->subDays($days))->where('status', '!=', 'failed')->delete();
        $this->info("Logs limpiados. Auditoría: {$audit}. Integraciones: {$integration}.");
        return self::SUCCESS;
    }
}
