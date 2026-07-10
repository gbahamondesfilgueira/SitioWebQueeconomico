<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Qué Económico') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <main class="auth-page d-flex align-items-center justify-content-center p-3">
        <div class="auth-card card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <div class="mb-4 text-center">
                    <a href="/" class="text-decoration-none text-dark">
                        <div class="fw-bold fs-4">Qué Económico</div>
                        <div class="text-secondary small">Ecommerce + POS</div>
                    </a>
                </div>

                {{ $slot }}
            </div>
        </div>
    </main>
</body>
</html>
