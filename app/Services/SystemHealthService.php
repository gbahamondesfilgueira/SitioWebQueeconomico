<?php

namespace App\Services;

use App\Models\IntegrationLog;
use App\Models\SystemHealthCheck;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SystemHealthService
{
    public function run(): array
    {
        $checks = [
            $this->check('Aplicacion', 'ok', 'Laravel '.app()->version().' en PHP '.PHP_VERSION),
            $this->database(),
            $this->storage(),
            $this->queue(),
            $this->failedIntegrations(),
        ];

        foreach ($checks as $check) {
            SystemHealthCheck::query()->create($check + ['checked_at' => now()]);
        }

        return $checks;
    }

    public function latest(): array
    {
        return [
            'checks' => SystemHealthCheck::query()->latest('checked_at')->limit(20)->get(),
            'errors' => $this->latestLaravelErrors(),
            'failed_integrations' => IntegrationLog::query()->with('integration')->where('status', 'failed')->latest('created_at')->limit(10)->get(),
            'storage_usage' => $this->directorySize(storage_path()),
        ];
    }

    private function database(): array
    {
        try {
            DB::select('select 1');
            return $this->check('Base de datos', 'ok', 'Conexion disponible');
        } catch (Throwable $exception) {
            return $this->check('Base de datos', 'error', $exception->getMessage());
        }
    }

    private function storage(): array
    {
        return is_writable(storage_path())
            ? $this->check('Storage', 'ok', 'Directorio storage escribible')
            : $this->check('Storage', 'error', 'Directorio storage no escribible');
    }

    private function queue(): array
    {
        return $this->check('Queue', 'ok', 'Conexion de cola: '.config('queue.default'));
    }

    private function failedIntegrations(): array
    {
        $count = IntegrationLog::query()->where('status', 'failed')->where('created_at', '>=', now()->subDay())->count();

        return $this->check('Integraciones', $count > 0 ? 'warning' : 'ok', "{$count} logs fallidos en 24 horas");
    }

    private function check(string $name, string $status, string $message, array $metadata = []): array
    {
        return ['check_name' => $name, 'status' => $status, 'message' => $message, 'metadata' => $metadata];
    }

    private function latestLaravelErrors(): array
    {
        $file = storage_path('logs/laravel.log');
        if (! File::exists($file)) {
            return [];
        }

        return collect(explode("\n", File::get($file)))->filter(fn ($line) => str_contains($line, 'ERROR'))->take(-20)->values()->all();
    }

    private function directorySize(string $path): int
    {
        if (! File::exists($path)) {
            return 0;
        }

        return collect(File::allFiles($path))->sum(fn ($file) => $file->getSize());
    }
}
