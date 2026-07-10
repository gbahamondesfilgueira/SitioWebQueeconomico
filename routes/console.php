<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('catalog:import-categories {path}', function (string $path) {
    if (! is_file($path)) {
        $this->error("No existe el archivo: {$path}");

        return self::FAILURE;
    }

    $handle = fopen($path, 'rb');
    $headers = array_map(fn ($header) => trim((string) $header, "\xEF\xBB\xBF \t\n\r\0\x0B"), fgetcsv($handle) ?: []);
    $categoryIndex = array_search('Categorías', $headers ?: [], true);

    if ($categoryIndex === false) {
        $categoryIndex = array_search('Categorias', $headers ?: [], true);
    }

    if ($categoryIndex === false) {
        $this->error('El CSV debe tener una columna Categorías.');
        fclose($handle);

        return self::FAILURE;
    }

    $created = 0;
    $existing = 0;
    $paths = 0;

    while (($row = fgetcsv($handle)) !== false) {
        $rawCategories = $row[$categoryIndex] ?? '';

        foreach (array_filter(array_map('trim', explode(',', $rawCategories))) as $categoryPath) {
            $parentId = null;
            $segments = array_filter(array_map('trim', explode('>', $categoryPath)));

            foreach ($segments as $segment) {
                $name = trim($segment);

                if ($name === '') {
                    continue;
                }

                $category = \App\Models\Category::query()
                    ->where('parent_id', $parentId)
                    ->whereRaw('LOWER(name) = ?', [Str::lower($name)])
                    ->first();

                if ($category) {
                    $existing++;
                } else {
                    $category = \App\Models\Category::query()->create([
                        'parent_id' => $parentId,
                        'name' => $name,
                        'is_active' => true,
                        'sort_order' => 0,
                    ]);
                    $created++;
                }

                $parentId = $category->id;
            }

            $paths++;
        }
    }

    fclose($handle);
    $this->info("Rutas procesadas: {$paths}");
    $this->info("Categorias creadas: {$created}");
    $this->info("Categorias existentes omitidas: {$existing}");

    return self::SUCCESS;
})->purpose('Importa categorias unicas desde CSV WooCommerce');

Artisan::command('users:set-super-admin {email} {password}', function (string $email, string $password) {
    $role = \App\Models\Role::query()->where('slug', 'super-admin')->firstOrFail();
    $user = \App\Models\User::query()
        ->where('email', 'admin.qe@erp.local')
        ->orWhere('email', $email)
        ->orWhereHas('role', fn ($query) => $query->where('slug', 'super-admin'))
        ->firstOrFail();

    $user->forceFill([
        'name' => 'Guillermo Bahamondes Filgueira',
        'email' => $email,
        'password' => \Illuminate\Support\Facades\Hash::make($password),
        'role_id' => $role->id,
        'is_active' => true,
        'two_factor_code' => null,
        'two_factor_expires_at' => null,
        'locked_until' => null,
        'failed_login_attempts' => 0,
    ])->save();

    $this->info("Super admin actualizado: {$user->email}");

    return self::SUCCESS;
})->purpose('Actualiza credenciales del super admin');

Artisan::command('mail:test {email}', function (string $email) {
    \Illuminate\Support\Facades\Mail::raw(
        'Correo de prueba enviado desde Que Economico. Si recibes este mensaje, SMTP esta funcionando correctamente.',
        function ($message) use ($email) {
            $message->to($email)->subject('Prueba SMTP Que Economico');
        }
    );

    $this->info("Correo de prueba enviado a {$email}");

    return self::SUCCESS;
})->purpose('Envia un correo de prueba SMTP');
