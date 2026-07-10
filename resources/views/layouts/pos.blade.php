<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'POS') - Qué Económico</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('pos.dashboard') }}">POS Qué Económico</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#posNav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="posNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('pos.sale.create') }}">Venta</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('pos.cash.current') }}">Caja</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('pos.quotes.index') }}">Cotizaciones</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('pos.reservations.index') }}">Reservas</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Admin</a></li>
            </ul>
            <span class="navbar-text">{{ auth()->user()->name }}</span>
        </div>
    </div>
</nav>
<main class="container-fluid py-3">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
    @yield('content')
</main>
</body>
</html>
