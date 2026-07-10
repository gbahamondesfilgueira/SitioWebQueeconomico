@extends('layouts.pos')

@section('title', 'Nueva venta')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="bg-white border rounded p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <h1 class="h4 mb-0">Venta POS</h1>
                    <small class="text-muted">{{ $terminal->name }} / Bodega {{ $terminal->warehouse->name ?? '' }}</small>
                </div>
                <form method="POST" action="{{ route('pos.cart.clear') }}">@csrf<button class="btn btn-outline-danger">Vaciar</button></form>
            </div>
            @include('pos.components.product-search')
            @include('pos.components.cart-items')
        </div>
    </div>
    <div class="col-lg-4">
        @include('pos.components.customer-panel')
        @include('pos.components.payment-panel')
        @include('pos.components.summary')
    </div>
</div>
@endsection
