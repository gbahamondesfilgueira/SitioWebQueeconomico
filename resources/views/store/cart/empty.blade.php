@extends('layouts.store')

@section('content')
    <div class="container py-5">
        <div class="card border-0 shadow-sm text-center"><div class="card-body p-5"><h1 class="h3">Tu carrito está vacío</h1><p class="text-secondary">Agrega productos o packs para iniciar checkout.</p><a href="{{ route('store.shop') }}" class="btn btn-dark">Ir a la tienda</a></div></div>
    </div>
@endsection
