@extends('layouts.store')

@section('content')
    <div class="container py-4">
        <x-store.breadcrumb :items="['Packs' => route('store.packs.index'), $pack->name => route('store.packs.show', $pack->slug)]" />

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="ratio ratio-1x1 bg-light rounded shadow-sm">
                    @if($display['image'])
                        <img src="{{ $display['image'] }}" class="object-fit-cover" alt="{{ $pack->name }}">
                    @endif
                </div>
            </div>

            <div class="col-lg-7">
                <h1>{{ $pack->name }}</h1>
                <p class="text-secondary">{{ $pack->description }}</p>
                <div class="mb-2">
                    <span class="text-decoration-line-through text-secondary">${{ number_format($display['normal_price'], 0, ',', '.') }}</span>
                </div>
                <div class="display-6 fw-bold text-danger">${{ number_format($display['pack_price'], 0, ',', '.') }}</div>
                <div class="mb-3">
                    Ahorras ${{ number_format($display['saving'], 0, ',', '.') }}
                    @if($display['saving_percentage'])
                        ({{ $display['saving_percentage'] }}%)
                    @endif
                </div>
                <span class="badge {{ $display['stock'] <= 0 ? 'text-bg-secondary' : ($display['stock'] <= 5 ? 'text-bg-warning' : 'text-bg-success') }}">{{ $display['stock_label'] }}</span>

                <form method="POST" action="{{ route('store.cart.add') }}" class="mt-3" data-cart-add-form>
                    @csrf
                    <input type="hidden" name="item_type" value="pack">
                    <input type="hidden" name="product_pack_id" value="{{ $pack->id }}">
                    <div class="input-group">
                        <input type="number" name="quantity" value="1" min="1" step="1" class="form-control">
                        <button class="btn btn-dark btn-lg" @disabled($display['stock'] <= 0)>Agregar pack</button>
                    </div>
                </form>
            </div>
        </div>

        <section class="mt-5">
            <h2 class="h4">Productos incluidos</h2>
            <div class="row g-3">
                @foreach($pack->items as $item)
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="fw-semibold">{{ $item->product?->name }}</div>
                                <div class="small text-secondary">{{ $item->variant?->name }} · Cantidad: {{ (int) $item->quantity }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
@endsection
