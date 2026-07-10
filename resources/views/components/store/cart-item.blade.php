@props(['item'])
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body d-flex gap-3 align-items-center">
        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:84px;height:84px">
            @php($image = $item->product?->images?->first()?->image_path)
            @if($image)<img src="{{ Storage::url($image) }}" class="object-fit-cover rounded" style="width:84px;height:84px" alt="">@else<span class="small text-secondary">Item</span>@endif
        </div>
        <div class="flex-grow-1">
            <div class="fw-semibold">{{ $item->item_type === 'pack' ? $item->pack?->name : $item->product?->name }}</div>
            <div class="small text-secondary">{{ $item->variant?->name ?: $item->variant?->sku }}</div>
            <div class="small">@if($item->line_discount > 0)<span class="text-decoration-line-through text-secondary">${{ number_format((float) $item->regular_price, 0, ',', '.') }}</span>@endif <span class="fw-semibold">${{ number_format((float) $item->final_unit_price, 0, ',', '.') }}</span></div>
        </div>
        <form method="POST" action="{{ route('store.cart.update') }}" class="d-flex gap-2 align-items-center">
            @csrf
            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control" style="width:90px">
            <button class="btn btn-outline-secondary btn-sm">Actualizar</button>
        </form>
        <div class="fw-bold">${{ number_format((float) $item->line_total, 0, ',', '.') }}</div>
        <form method="POST" action="{{ route('store.cart.remove') }}">
            @csrf
            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
            <button class="btn btn-outline-danger btn-sm">Eliminar</button>
        </form>
    </div>
</div>
