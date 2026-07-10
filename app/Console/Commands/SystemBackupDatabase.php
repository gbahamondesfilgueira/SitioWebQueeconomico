<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class SystemBackupDatabase extends Command
{
    protected $signature = 'system:backup-database';
    protected $description = 'Crea un backup SQL de la base de datos.';

    public function handle(BackupService $backups): int
    {
        $backup = $backups->createDatabaseBackup();
        $this->info("Backup {$backup->status}: {$backup->file_path}");
        return $backup->status === 'completed' ? self::SUCCESS : self::FAILURE;
    }
}
