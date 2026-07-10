@extends('layouts.store')
@section('title', 'Servicio no disponible')
@section('content')
    <section class="container py-5 text-center">
        <h1 class="display-5 fw-bold">503</h1>
        <p class="lead">El servicio está temporalmente en mantenimiento.</p>
        <a href="{{ route('store.home') }}" class="btn btn-dark">Volver al inicio</a>
    </section>
@endsection
