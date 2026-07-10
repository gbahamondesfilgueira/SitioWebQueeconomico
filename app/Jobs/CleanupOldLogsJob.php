<?php

namespace App\Jobs;

use App\Models\AuditLog;
use App\Models\IntegrationLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CleanupOldLogsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $days = 90)
    {
    }

    public function handle(): void
    {
        AuditLog::query()->where('created_at', '<', now()->subDays($this->days))->delete();
        IntegrationLog::query()->where('created_at', '<', now()->subDays($this->days))->where('status', '!=', 'failed')->delete();
    }
}
