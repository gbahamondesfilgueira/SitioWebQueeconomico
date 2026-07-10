<?php

namespace App\Jobs;

use App\Services\AuditLogger;
use App\Services\ReportExportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class ExportReportJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(public string $reportType, public string $format = 'csv', public array $filters = [])
    {
    }

    public function handle(ReportExportService $exports): void
    {
        AuditLogger::record('queued', 'report_exports', "Exportación {$this->reportType} preparada en cola.");
    }

    public function failed(Throwable $exception): void
    {
        AuditLogger::record('failed', 'report_exports', $exception->getMessage());
    }
}
