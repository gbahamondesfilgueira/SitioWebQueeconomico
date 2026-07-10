@php($setting = \App\Models\Setting::current())
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mi cuenta') | {{ $setting->store_name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="account-page">
    <header class="account-header border-bottom bg-white">
        <div class="container py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <a href="{{ route('account.dashboard') }}" class="text-dark text-decoration-none fw-bold fs-5">{{ $setting->store_name }}</a>
            <nav class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('store.home') }}" class="btn btn-sm btn-outline-secondary">Home</a>
                <a href="{{ route('store.shop') }}" class="btn btn-sm btn-outline-secondary">Tienda</a>
                <a href="{{ route('store.account.orders.index') }}" class="btn btn-sm btn-outline-secondary">Mis compras</a>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button class="btn btn-sm btn-dark">Salir</button>
                </form>
            </nav>
        </div>
    </header>

    <div class="container py-4">
        <div class="row g-3">
            <aside class="col-lg-3">
                <div class="list-group shadow-sm account-side-nav">
                    <a class="list-group-item list-group-item-action {{ request()->routeIs('account.dashboard') ? 'active' : '' }}" href="{{ route('account.dashboard') }}">Resumen</a>
                    <a class="list-group-item list-group-item-action {{ request()->routeIs('account.profile') ? 'active' : '' }}" href="{{ route('account.profile') }}">Mi Perfil</a>
                    <a class="list-group-item list-group-item-action {{ request()->routeIs('account.addresses') ? 'active' : '' }}" href="{{ route('account.addresses') }}">Mis Direcciones</a>
                    <a class="list-group-item list-group-item-action {{ request()->routeIs('account.companies') ? 'active' : '' }}" href="{{ route('account.companies') }}">Mis Empresas</a>
                    <a class="list-group-item list-group-item-action {{ request()->routeIs('account.favorites') ? 'active' : '' }}" href="{{ route('account.favorites') }}">Mis Favoritos</a>
                    <a class="list-group-item list-group-item-action {{ request()->routeIs('account.wishlist') ? 'active' : '' }}" href="{{ route('account.wishlist') }}">Mi Lista de Deseos</a>
                    <a class="list-group-item list-group-item-action {{ request()->routeIs('account.documents') ? 'active' : '' }}" href="{{ route('account.documents') }}">Mis Documentos</a>
                    <a class="list-group-item list-group-item-action {{ request()->routeIs('account.rewards') ? 'active' : '' }}" href="{{ route('account.rewards') }}">Mis Puntos</a>
                    <a class="list-group-item list-group-item-action {{ request()->routeIs('account.coupons') ? 'active' : '' }}" href="{{ route('account.coupons') }}">Mis Cupones</a>
                    <a class="list-group-item list-group-item-action {{ request()->routeIs('account.security') ? 'active' : '' }}" href="{{ route('account.security') }}">Seguridad</a>
                </div>
            </aside>

            <main class="col-lg-9">
                @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                @if(session('status'))<div class="alert alert-info">{{ session('status') }}</div>@endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
