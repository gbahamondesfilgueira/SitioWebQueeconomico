<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SystemSecurityCheck extends Command
{
    protected $signature = 'system:security-check';
    protected $description = 'Revisa configuracion basica de seguridad para produccion.';

    public function handle(): int
    {
        $checks = [
            'APP_DEBUG=false' => ! config('app.debug'),
            'APP_KEY configurada' => filled(config('app.key')),
            'APP_URL configurada' => filled(config('app.url')),
            'Storage escribible' => is_writable(storage_path()),
            'bootstrap/cache escribible' => is_writable(base_path('bootstrap/cache')),
            '.env no versionado' => File::exists(base_path('.gitignore')) && str_contains(File::get(base_path('.gitignore')), '.env'),
            '.env.example sin password real' => ! $this->envExampleLooksSensitive(),
            'Backups fuera de public' => ! str_starts_with(realpath(storage_path('app/private/backups')) ?: storage_path('app/private/backups'), public_path()),
            'HTTPS en produccion' => app()->environment('production') ? str_starts_with((string) config('app.url'), 'https://') : true,
            'Sesion cifrada recomendada' => (bool) config('session.encrypt'),
        ];

        $failed = 0;

        foreach ($checks as $name => $ok) {
            $this->line(($ok ? 'OK' : 'WARN').' - '.$name);
            $failed += $ok ? 0 : 1;
        }

        return $failed === 0 ? self::SUCCESS : self::FAILURE;
    }

    private function envExampleLooksSensitive(): bool
    {
        $path = base_path('.env.example');

        if (! File::exists($path)) {
            return false;
        }

        $content = File::get($path);

        return str_contains($content, 'root1234')
            || str_contains($content, 'noreply@queeconomico.cl')
            || str_contains($content, 'MAIL_PASSWORD=Gb');
    }
}
