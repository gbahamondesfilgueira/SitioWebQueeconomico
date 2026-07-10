@props(['variants' => []])
@if(count($variants))
    <div class="mb-3">
        <label class="form-label fw-semibold">Variantes</label>
        <select class="form-select" id="variantSelector">
            @foreach($variants as $variant)
                <option value="{{ $variant['id'] }}">{{ $variant['name'] }} · ${{ number_format($variant['final_price'], 0, ',', '.') }} · {{ $variant['stock_label'] }}</option>
            @endforeach
        </select>
        <div class="small text-secondary mt-2">El selector queda preparado para actualizar precio e imagen en la fase de carrito.</div>
    </div>
@endif
