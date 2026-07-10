@foreach ($config['fields'] as $field)
    @php
        $name = $field['name'];
        $type = $field['type'] ?? 'text';
        $value = old($name, $item->{$name});
    @endphp

    @if ($type === 'checkbox')
        <div class="col-12">
            <div class="form-check form-switch">
                <input class="form-check-input @error($name) is-invalid @enderror" type="checkbox" role="switch" id="{{ $name }}" name="{{ $name }}" value="1" @checked(old($name, $item->{$name}))>
                <label class="form-check-label" for="{{ $name }}">{{ $field['label'] }}</label>
                @error($name) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
        </div>
    @else
        <div class="{{ $type === 'textarea' ? 'col-12' : 'col-md-6' }}">
            <label class="form-label" for="{{ $name }}">{{ $field['label'] }}</label>

            @if ($type === 'textarea')
                <textarea class="form-control @error($name) is-invalid @enderror" id="{{ $name }}" name="{{ $name }}" rows="4">{{ $value }}</textarea>
            @elseif ($type === 'select')
                <select class="form-select @error($name) is-invalid @enderror" id="{{ $name }}" name="{{ $name }}">
                    <option value="">Selecciona una opción</option>
                    @foreach ($config['typeOptions'] as $optionValue => $optionLabel)
                        <option value="{{ $optionValue }}" @selected($value === $optionValue)>{{ $optionLabel }}</option>
                    @endforeach
                </select>
            @else
                <input class="form-control @error($name) is-invalid @enderror" id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ $value }}" @if($type === 'number') step="0.01" @endif>
            @endif

            @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    @endif
@endforeach

@if ($config['hasActiveToggle'])
    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', $item->exists ? $item->is_active : true))>
            <label class="form-check-label" for="is_active">Activo</label>
        </div>
    </div>
@endif

<div class="col-12 d-flex justify-content-end gap-2">
    <a class="btn btn-outline-secondary" href="{{ route($config['routePrefix'].'.index') }}">Cancelar</a>
    <button class="btn btn-primary" type="submit">Guardar</button>
</div>
