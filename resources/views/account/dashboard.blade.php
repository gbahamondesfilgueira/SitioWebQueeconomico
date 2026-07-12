@extends('layouts.account')

@section('title', 'Mi cuenta')

@section('content')
    <section class="card border-0 shadow-sm mb-3">
        <div class="card-body p-4">
            <h1 class="h3 mb-1">Hola, {{ $customer->first_name }}</h1>
            <p class="text-secondary mb-3">
                Desde aqui puedes revisar tus compras, editar tu perfil y gestionar tus datos de envio.
            </p>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('store.account.orders.index') }}" class="btn btn-dark">
                    Mis compras
                </a>

                <a href="{{ route('account.profile') }}" class="btn btn-outline-dark">
                    Perfil
                </a>

                <a href="{{ route('store.pages.show', 'contacto') }}" class="btn btn-outline-secondary">
                    Ayuda
                </a>
            </div>
        </div>
    </section>

    @if($customer->addresses_count < 1)
        <div class="alert alert-warning border-0 shadow-sm">
            Aun no tienes direccion de envio. Agregala para que checkout use la misma informacion como direccion de facturacion.

            <a href="{{ route('account.addresses') }}" class="alert-link">
                Agregar direccion
            </a>
        </div>
    @endif

    <section class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong>Ultimas compras</strong>

            <a href="{{ route('store.account.orders.index') }}" class="small">
                Ver todas
            </a>
        </div>

        <div class="card-body">
            @forelse($orders as $order)
                <a
                    href="{{ route('store.account.orders.show', $order->order_number) }}"
                    class="account-order-row"
                >
                    <span>
                        <strong>{{ $order->order_number }}</strong>

                        <small class="text-secondary d-block">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                            ·
                            {{ $order->order_status }}
                        </small>
                    </span>

                    <span class="fw-bold">
                        ${{ number_format((float) $order->grand_total, 0, ',', '.') }}
                    </span>
                </a>
            @empty
                <div class="text-center py-4">
                    <p class="text-secondary mb-3">
                        Todavia no tienes compras registradas.
                    </p>

                    <a href="{{ route('store.shop') }}" class="btn btn-dark">
                        Ir a la tienda
                    </a>
                </div>
            @endforelse
        </div>
    </section>

    <section class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong>Mi lista de deseos</strong>

            <a href="{{ route('account.wishlist') }}" class="small">
                Ver lista
            </a>
        </div>

        <div class="card-body">
            <div class="row g-3">
                @forelse($wishlistItems as $item)
                    <div class="col-6 col-md-4">
                        <a
                            class="account-wishlist-item"
                            href="{{ $item->product
                                ? route('store.products.show', $item->product->slug)
                                : route('store.shop') }}"
                        >
                            <div class="ratio ratio-1x1 bg-light rounded overflow-hidden mb-2">
                                @php
                                    $image = $item->product?->images?->first()?->image_path;

                                    if ($image) {
                                        $image = ltrim($image, '/');

                                        if (str_starts_with($image, 'storage/')) {
                                            $image = substr($image, strlen('storage/'));
                                        }
                                    }
                                @endphp

                                @if($image)
                                    <img
                                        src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image) }}"
                                        alt="{{ $item->product?->name }}"
                                        class="object-fit-cover"
                                    >
                                @endif
                            </div>

                            <span>
                                {{ $item->product?->name ?? 'Producto no disponible' }}
                            </span>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <p class="text-secondary mb-3">
                            No tienes productos marcados en tu lista de deseos.
                        </p>

                        <a href="{{ route('store.shop') }}" class="btn btn-outline-dark">
                            Explorar productos
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
