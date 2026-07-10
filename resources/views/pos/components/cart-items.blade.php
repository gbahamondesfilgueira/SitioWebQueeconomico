<div class="table-responsive">
    <table class="table align-middle">
        <thead><tr><th>Producto</th><th style="width:120px">Cant.</th><th>Precio</th><th>Total</th><th></th></tr></thead>
        <tbody>
        @forelse($cart->items as $item)
            <tr>
                <td>
                    <strong>{{ $item->item_type === 'pack' ? $item->pack?->name : $item->product?->name }}</strong>
                    <div class="text-muted small">{{ $item->variant?->name }} {{ $item->variant?->sku ?? $item->product?->sku }}</div>
                </td>
                <td>
                    <form method="POST" action="{{ route('pos.cart.update') }}" class="d-flex gap-1">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <input name="quantity" type="number" min="1" value="{{ $item->quantity }}" class="form-control form-control-sm">
                        <button class="btn btn-sm btn-outline-secondary">OK</button>
                    </form>
                </td>
                <td>${{ number_format($item->final_unit_price, 0, ',', '.') }}</td>
                <td>${{ number_format($item->line_total, 0, ',', '.') }}</td>
                <td>
                    <form method="POST" action="{{ route('pos.cart.remove') }}">@csrf<input type="hidden" name="item_id" value="{{ $item->id }}"><button class="btn btn-sm btn-outline-danger">Quitar</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-muted">Agrega productos para iniciar la venta.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
