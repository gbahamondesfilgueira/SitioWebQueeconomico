<?php

namespace App\Console\Commands;

use App\Services\SystemHealthService;
use Illuminate\Console\Command;

class SystemHealthCheck extends Command
{
    protected $signature = 'system:health-check';
    protected $description = 'Ejecuta verificaciones básicas de salud del sistema.';

    public function handle(SystemHealthService $health): int
    {
        foreach ($health->run() as $check) {
            $this->line(strtoupper($check['status']).' - '.$check['check_name'].': '.$check['message']);
        }

        return self::SUCCESS;
    }
}
