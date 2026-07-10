@extends('layouts.store')
@section('title', 'Sesión expirada')
@section('content')
    <section class="container py-5 text-center">
        <h1 class="display-5 fw-bold">419</h1>
        <p class="lead">Tu sesión expiró. Recarga la página e intenta nuevamente.</p>
        <a href="{{ url()->previous() }}" class="btn btn-dark">Volver</a>
    </section>
@endsection
