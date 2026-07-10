<?php

namespace App\Services;

use App\Models\SystemBackup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Throwable;
use ZipArchive;

class BackupService
{
    public function createDatabaseBackup(?int $userId = null): SystemBackup
    {
        $backup = SystemBackup::query()->create([
            'backup_type' => 'database',
            'status' => 'running',
            'started_at' => now(),
            'created_by' => $userId,
        ]);

        try {
            $path = 'backups/database/database-'.now()->format('Ymd-His').'.sql';
            Storage::disk('local')->put($path, $this->databaseDump());

            $backup->update([
                'status' => 'completed',
                'file_path' => $path,
                'file_size' => Storage::disk('local')->size($path),
                'finished_at' => now(),
            ]);

            AuditLogger::record('created', 'backups', 'Backup de base de datos creado.');
        } catch (Throwable $exception) {
            $backup->update(['status' => 'failed', 'error_message' => $exception->getMessage(), 'finished_at' => now()]);
            AuditLogger::record('failed', 'backups', 'Backup fallido: '.$exception->getMessage());
        }

        return $backup;
    }

    public function createFilesBackup(?int $userId = null): SystemBackup
    {
        $backup = SystemBackup::query()->create(['backup_type' => 'files', 'status' => 'running', 'started_at' => now(), 'created_by' => $userId]);

        try {
            $path = storage_path('app/backups/files/files-'.now()->format('Ymd-His').'.zip');
            File::ensureDirectoryExists(dirname($path));

            $zip = new ZipArchive();
            if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new \RuntimeException('No se pudo crear el archivo ZIP.');
            }

            foreach ([storage_path('app/public')] as $source) {
                if (File::exists($source)) {
                    $this->addToZip($zip, $source, basename($source));
                }
            }
            $zip->close();

            $relative = 'backups/files/'.basename($path);
            $backup->update(['status' => 'completed', 'file_path' => $relative, 'file_size' => filesize($path), 'finished_at' => now()]);
        } catch (Throwable $exception) {
            $backup->update(['status' => 'failed', 'error_message' => $exception->getMessage(), 'finished_at' => now()]);
        }

        return $backup;
    }

    public function createFullBackup(?int $userId = null): SystemBackup
    {
        $database = $this->createDatabaseBackup($userId);
        $files = $this->createFilesBackup($userId);

        return $files->status === 'failed' ? $files : $database;
    }

    public function listBackups()
    {
        return SystemBackup::query()->latest()->paginate(20);
    }

    public function deleteOldBackups(int $days = 30): int
    {
        $deleted = 0;
        $backups = SystemBackup::query()->where('created_at', '<', now()->subDays($days))->get();

        foreach ($backups as $backup) {
            if ($backup->file_path) {
                Storage::disk('local')->delete($backup->file_path);
            }
            $backup->delete();
            $deleted++;
        }

        return $deleted;
    }

    public function validateBackupFile(SystemBackup $backup): bool
    {
        return $backup->file_path !== null && Storage::disk('local')->exists($backup->file_path);
    }

    private function databaseDump(): string
    {
        $tables = collect(DB::select('SHOW TABLES'))->map(fn ($row) => array_values((array) $row)[0]);
        $sql = "-- Backup generado ".now()->toDateTimeString()."\n\n";

        foreach ($tables as $table) {
            $create = DB::select("SHOW CREATE TABLE `{$table}`")[0]->{'Create Table'};
            $sql .= "DROP TABLE IF EXISTS `{$table}`;\n{$create};\n\n";

            foreach (DB::table($table)->cursor() as $row) {
                $values = collect((array) $row)->map(fn ($value) => $value === null ? 'NULL' : DB::getPdo()->quote((string) $value))->implode(', ');
                $columns = collect(array_keys((array) $row))->map(fn ($column) => "`{$column}`")->implode(', ');
                $sql .= "INSERT INTO `{$table}` ({$columns}) VALUES ({$values});\n";
            }
            $sql .= "\n";
        }

        return $sql;
    }

    private function addToZip(ZipArchive $zip, string $source, string $name): void
    {
        if (is_file($source)) {
            $zip->addFile($source, $name);
            return;
        }

        foreach (File::allFiles($source) as $file) {
            $zip->addFile($file->getRealPath(), $name.'/'.str_replace('\\', '/', $file->getRelativePathname()));
        }
    }
}
