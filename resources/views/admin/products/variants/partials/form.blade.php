
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Datos de variante</div>
            <div class="card-body row g-3">
                <div class="col-md-4"><label class="form-label" for="sku">SKU</label><input class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $variant->sku) }}">@error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label" for="barcode">Código de barras</label><input class="form-control @error('barcode') is-invalid @enderror" id="barcode" name="barcode" value="{{ old('barcode', $variant->barcode) }}">@error('barcode')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label" for="name">Nombre interno</label><input class="form-control" id="name" name="name" value="{{ old('name', $variant->name) }}"></div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Atributos</div>
            <div class="card-body row g-3">
                @php $selectedValues = collect(old('attribute_value_ids', $variant->attributeValues->pluck('id')->all())); @endphp
                @foreach ($attributes as $attribute)
                    <div class="col-md-6">
                        <label class="form-label">{{ $attribute->name }}</label>
                        <select class="form-select" name="attribute_value_ids[]">
                            <option value="">Sin valor</option>
                            @foreach ($attribute->values as $value)
                                <option value="{{ $value->id }}" @selected($selectedValues->contains($value->id))>{{ $value->value }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
                @error('attribute_value_ids')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Precios y oferta</div>
            <div class="card-body row g-3">
                @foreach ([['cost_price','Costo'], ['regular_price','Precio normal'], ['sale_price','Precio oferta']] as [$field, $label])
                    <div class="col-md-4"><label class="form-label" for="{{ $field }}">{{ $label }}</label><input class="form-control @error($field) is-invalid @enderror" id="{{ $field }}" name="{{ $field }}" type="number" min="0" step="0.01" value="{{ old($field, $variant->{$field}) }}">@error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                @endforeach
                <div class="col-md-6"><label class="form-label" for="sale_starts_at">Inicio oferta</label><input class="form-control" id="sale_starts_at" name="sale_starts_at" type="datetime-local" value="{{ old('sale_starts_at', optional($variant->sale_starts_at)->format('Y-m-d\TH:i')) }}"></div>
                <div class="col-md-6"><label class="form-label" for="sale_ends_at">Término oferta</label><input class="form-control" id="sale_ends_at" name="sale_ends_at" type="datetime-local" value="{{ old('sale_ends_at', optional($variant->sale_ends_at)->format('Y-m-d\TH:i')) }}"></div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Peso y medidas</div>
            <div class="card-body row g-3">
                @foreach ([['weight','Peso'], ['height','Alto'], ['width','Ancho'], ['length','Largo']] as [$field, $label])
                    <div class="col-md-3"><label class="form-label" for="{{ $field }}">{{ $label }}</label><input class="form-control" id="{{ $field }}" name="{{ $field }}" type="number" min="0" step="0.001" value="{{ old($field, $variant->{$field}) }}"></div>
                @endforeach
                <div class="col-md-6"><label class="form-label" for="weight_unit_id">Unidad peso</label><select class="form-select" id="weight_unit_id" name="weight_unit_id"><option value="">Sin unidad</option>@foreach ($weightUnits as $unit)<option value="{{ $unit->id }}" @selected(old('weight_unit_id', $variant->weight_unit_id) == $unit->id)>{{ $unit->name }} ({{ $unit->code }})</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label" for="dimension_unit_id">Unidad dimensión</label><select class="form-select" id="dimension_unit_id" name="dimension_unit_id"><option value="">Sin unidad</option>@foreach ($dimensionUnits as $unit)<option value="{{ $unit->id }}" @selected(old('dimension_unit_id', $variant->dimension_unit_id) == $unit->id)>{{ $unit->name }} ({{ $unit->code }})</option>@endforeach</select></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Estado e imagen</div>
            <div class="card-body">
                <div class="form-check form-switch mb-3"><input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $variant->exists ? $variant->is_active : true))><label class="form-check-label" for="is_active">Activo</label></div>
                <label class="form-label" for="image">Imagen variante</label>
                <input class="form-control" id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp">
                @if ($variant->image_path)
    @php
        $variantImage = ltrim($variant->image_path, '/');

        if (str_starts_with($variantImage, 'storage/')) {
            $variantImage = substr($variantImage, strlen('storage/'));
        }
    @endphp

    <img
        src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($variantImage) }}"
        class="img-fluid rounded mt-3"
        alt="Variante"
    >
@endif
            </div>
        </div>
        <div class="d-grid gap-2">
            <button class="btn btn-primary">Guardar variante</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.products.variants.index', $product) }}">Cancelar</a>
        </div>
    </div>
</div>
