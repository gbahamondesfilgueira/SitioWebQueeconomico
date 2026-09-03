@props(['display'])
@php($product = $display['product'])
@php($defaultVariant = collect($display['variants'] ?? [])->firstWhere('stock', '>', 0) ?: collect($display['variants'] ?? [])->first())
<div class="card h-100 border-0 shadow-sm product-card">
    <a href="{{ route('store.products.show', $product->slug) }}" class="ratio ratio-1x1 bg-light text-decoration-none">
        @if($display['image'])
            <img src="{{ $display['image'] }}" alt="{{ $product->name }}" class="card-img-top object-fit-cover">
        @else
            <div class="d-flex align-items-center justify-content-center text-secondary">Sin imagen</div>
        @endif
    </a>
    <div class="card-body">
        <div class="small text-secondary">{{ $product->brand?->name }}</div>
        <a href="{{ route('store.products.show', $product->slug) }}" class="text-dark text-decoration-none fw-semibold">{{ $product->name }}</a>
        <div class="mt-2"><x-store.price :display="$display" /></div>
        <div class="mt-2"><span class="badge {{ $display['stock_class'] }}">{{ $display['stock_label'] }}</span></div>
        @if($display['show_local_stock'] ?? false)
            <div class="small text-secondary mt-2">
                Cerca de ti ({{ $display['local_stock_region'] }}):
                <span class="badge {{ $display['local_stock_class'] }}">{{ $display['local_stock_label'] }}</span>
                @if($display['local_stock_warehouse'])<span class="d-block">{{ $display['local_stock_warehouse'] }}</span>@endif
            </div>
        @endif
        @if($display['delivery_estimate'] ?? null)
            <div class="small text-secondary mt-2">Entrega: {{ $display['delivery_estimate'] }}</div>
        @endif
    </div>
    <div class="card-footer bg-white border-0 pt-0">
        @if($display['can_purchase'])
            <form method="POST" action="{{ route('store.cart.add') }}" data-cart-add-form>
                @csrf
                <input type="hidden" name="item_type" value="product">
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                @if($product->product_type === 'variable' && $defaultVariant)
                    <input type="hidden" name="product_variant_id" value="{{ $defaultVariant['id'] }}">
                @endif
                <input type="hidden" name="quantity" value="1">
                <button class="btn btn-outline-dark w-100" @disabled($display['stock'] <= 0)>Agregar</button>
            </form>
        @elseif(auth()->check())
            <a class="btn btn-outline-dark w-100" href="{{ route('account.addresses') }}">Configurar dirección</a>
        @else
            <button class="btn btn-outline-dark w-100" type="button" data-bs-toggle="modal" data-bs-target="#authModal">Iniciar sesión para comprar</button>
        @endif
    </div>
</div>
