@props([
    'name' => 'region',
    'id' => null,
    'value' => null,
    'required' => false,
])

@php
    $regionService = app(\App\Services\DeliveryRegionService::class);
    $selectedRegion = $regionService->normalize(old($name, $value));
    $selectId = $id ?: $name;
@endphp

<select id="{{ $selectId }}" name="{{ $name }}" {{ $attributes->class(['form-select']) }} @required($required)>
    <option value="">Selecciona una región</option>
    @foreach($regionService->regions() as $code => $label)
        <option value="{{ $code }}" @selected($selectedRegion === $code)>{{ $label }}</option>
    @endforeach
</select>
