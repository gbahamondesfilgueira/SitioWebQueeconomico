@extends('layouts.store')
@section('title', 'Error interno')
@section('content')
    <section class="container py-5 text-center">
        <h1 class="display-5 fw-bold">500</h1>
        <p class="lead">Ocurrió un error interno. Nuestro equipo puede revisar los registros del sistema.</p>
        <a href="{{ route('store.home') }}" class="btn btn-dark">Volver al inicio</a>
    </section>
@endsection
