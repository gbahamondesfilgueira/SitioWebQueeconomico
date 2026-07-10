<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PDO;
use Throwable;

class InstallProduction extends Command
{
    protected $signature = 'app:install-production
        {--create-database : Intenta crear la base de datos si no existe}
        {--db-admin-user= : Usuario MySQL con permiso CREATE DATABASE, por defecto usa DB_USERNAME}
        {--db-admin-password= : Password MySQL admin, por defecto usa DB_PASSWORD}
        {--public-path= : Ruta absoluta del public real del dominio}
        {--admin-email= : Email del Super Admin}
        {--admin-password= : Password del Super Admin}
        {--seed : Ejecuta seeders base}
        {--fresh : Borra y recrea tablas. Usar solo en instalacion inicial}
        {--force : Confirma ejecucion en produccion}';

    protected $description = 'Instala y prepara la aplicacion en produccion/cPanel.';

    public function handle(): int
    {
        if (app()->environment('production') && ! $this->option('force')) {
            $this->error('Estas en produccion. Repite con --force para confirmar.');
            return self::FAILURE;
        }

        $this->info('== Instalacion productiva Que Economico ==');
        $this->clearCachedBootstrapFiles();

        if (! $this->ensureDatabase()) {
            return self::FAILURE;
        }

        $this->callArtisan('optimize:clear');

        $migrationCommand = $this->option('fresh') ? 'migrate:fresh' : 'migrate';
        $migrationOptions = ['--force' => true];
        if ($this->option('seed')) {
            $migrationOptions['--seed'] = true;
        }
        $this->callArtisan($migrationCommand, $migrationOptions);

        $this->ensureSuperAdmin();
        $this->linkStorage();

        foreach (['config:cache', 'route:cache', 'view:cache', 'event:cache'] as $command) {
            $this->callArtisan($command);
        }

        $this->info('Instalacion finalizada.');
        $this->line('Revisa el sitio y luego ejecuta: php artisan system:security-check');

        return self::SUCCESS;
    }

    private function ensureDatabase(): bool
    {
        $database = (string) config('database.connections.mysql.database');
        $host = (string) config('database.connections.mysql.host');
        $port = (string) config('database.connections.mysql.port');

        if (! $this->isSafeDatabaseName($database)) {
            $this->error("Nombre de base de datos invalido: {$database}");
            return false;
        }

        try {
            DB::connection()->getPdo();
            $this->info("Conexion OK con base de datos {$database}.");
            return true;
        } catch (Throwable $exception) {
            $this->warn('No se pudo conectar a la base configurada: '.$exception->getMessage());
        }

        if (! $this->option('create-database')) {
            $this->error('La base no existe o no conecta. Crea la DB o ejecuta con --create-database.');
            return false;
        }

        $user = (string) ($this->option('db-admin-user') ?: config('database.connections.mysql.username'));
        $password = (string) ($this->option('db-admin-password') ?: config('database.connections.mysql.password'));

        try {
            $pdo = new PDO("mysql:host={$host};port={$port};charset=utf8mb4", $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            DB::purge('mysql');
            DB::connection()->getPdo();
            $this->info("Base de datos {$database} lista.");
            return true;
        } catch (Throwable $exception) {
            $this->error('No fue posible crear/conectar la base: '.$exception->getMessage());
            return false;
        }
    }

    private function ensureSuperAdmin(): void
    {
        $email = $this->option('admin-email');
        $password = $this->option('admin-password');

        if (! $email || ! $password) {
            $this->warn('Super Admin no actualizado: usa --admin-email y --admin-password si quieres crearlo ahora.');
            return;
        }

        $role = Role::query()->firstOrCreate(
            ['slug' => 'super-admin'],
            ['name' => 'Super Admin', 'description' => 'Acceso total al sistema.'],
        );

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => Str::before($email, '@') ?: 'Super Admin',
                'role_id' => $role->id,
                'password' => $password,
                'is_active' => true,
                'auth_provider' => 'email',
                'email_verified_at' => now(),
            ],
        );

        $this->info("Super Admin listo: {$email}");
    }

    private function linkStorage(): void
    {
        $target = storage_path('app/public');
        $publicPath = $this->option('public-path') ?: public_path();
        $link = rtrim((string) $publicPath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'storage';

        File::ensureDirectoryExists($target);

        if (file_exists($link) || is_link($link)) {
            $this->warn("Storage link ya existe: {$link}");
            return;
        }

        try {
            symlink($target, $link);
            $this->info("Storage link creado: {$link}");
        } catch (Throwable $exception) {
            $this->warn('No se pudo crear symlink automaticamente: '.$exception->getMessage());
            $this->line("Comando manual: ln -s {$target} {$link}");
        }
    }

    private function callArtisan(string $command, array $options = []): void
    {
        $this->line("> php artisan {$command}");
        Artisan::call($command, $options);
        $output = trim(Artisan::output());
        if ($output !== '') {
            $this->line($output);
        }
    }

    private function isSafeDatabaseName(string $database): bool
    {
        return $database !== '' && preg_match('/^[A-Za-z0-9_]+$/', $database) === 1;
    }

    private function clearCachedBootstrapFiles(): void
    {
        foreach ([
            base_path('bootstrap/cache/config.php'),
            base_path('bootstrap/cache/routes-v7.php'),
            base_path('bootstrap/cache/events.php'),
            base_path('bootstrap/cache/packages.php'),
            base_path('bootstrap/cache/services.php'),
        ] as $file) {
            if (File::exists($file)) {
                File::delete($file);
                $this->line('Cache eliminada: '.basename($file));
            }
        }
    }
}
