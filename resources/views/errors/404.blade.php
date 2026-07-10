@extends('layouts.store')
@section('title', 'Página no encontrada')
@section('content')
    <section class="container py-5 text-center">
        <h1 class="display-5 fw-bold">404</h1>
        <p class="lead">La página que buscas no existe o fue movida.</p>
        <a href="{{ route('store.home') }}" class="btn btn-dark">Ir a la tienda</a>
    </section>
@endsection
