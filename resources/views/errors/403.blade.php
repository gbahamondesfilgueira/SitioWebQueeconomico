@extends('layouts.store')
@section('title', 'Acceso no autorizado')
@section('content')
    <section class="container py-5 text-center">
        <h1 class="display-5 fw-bold">403</h1>
        <p class="lead">No tienes permisos para acceder a esta sección.</p>
        <a href="{{ url('/') }}" class="btn btn-dark">Volver al inicio</a>
    </section>
@endsection
