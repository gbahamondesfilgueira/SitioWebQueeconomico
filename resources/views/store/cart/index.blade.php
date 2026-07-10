@extends('layouts.store')

@section('content')
    <div class="container py-4">
        <x-store.breadcrumb :items="['Carrito' => route('store.cart.index')]" />
        <h1 class="h3 mb-3">Carrito</h1>
        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
        <div class="row g-4">
            <div class="col-lg-8">
                @foreach($cart->items as $item)<x-store.cart-item :item="$item" />@endforeach
                <form method="POST" action="{{ route('store.cart.clear') }}">@csrf<button class="btn btn-outline-danger">Vaciar carrito</button></form>
            </div>
            <aside class="col-lg-4">
                <x-store.cart-summary :summary="$summary" />
                <x-store.coupon-form :cart="$cart" />
                <a href="{{ route('store.checkout.index') }}" class="btn btn-dark btn-lg w-100 mt-3">Iniciar checkout</a>
            </aside>
        </div>
    </div>
@endsection
