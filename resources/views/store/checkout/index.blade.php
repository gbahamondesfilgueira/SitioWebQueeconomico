@extends('layouts.store')

@section('content')
    <div class="container py-4">
        <x-store.breadcrumb :items="['Checkout' => route('store.checkout.index')]" />
        <h1 class="h3 mb-3">Checkout</h1>
        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
        <x-store.checkout-steps :step="$step" />
        <div class="row g-4">
            <section class="col-lg-8">
                @include("store.checkout.steps.$step")
            </section>
            <aside class="col-lg-4">
                <x-store.cart-summary :summary="$summary" />
            </aside>
        </div>
    </div>
@endsection
