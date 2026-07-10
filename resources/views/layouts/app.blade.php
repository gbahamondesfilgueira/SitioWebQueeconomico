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
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="{{ route('dashboard') }}">Qué Económico</a>
            <div class="ms-auto d-flex align-items-center gap-2">
                @auth
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('profile.edit') }}">Perfil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-dark btn-sm" type="submit">Salir</button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @isset($header)
            <div class="mb-4">{{ $header }}</div>
        @endisset
        {{ $slot }}
    </main>
</body>
</html>
