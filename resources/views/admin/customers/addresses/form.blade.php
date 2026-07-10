@extends('layouts.admin')

@section('title', $address->exists ? 'Editar dirección' : 'Nueva dirección')
@section('page-title', $address->exists ? 'Editar dirección' : 'Nueva dirección')

@section('content')
    <form method="POST" action="{{ $address->exists ? route('admin.customers.addresses.update', [$customer, $address]) : route('admin.customers.addresses.store', $customer) }}" class="card border-0 shadow-sm">
        @csrf
        @if($address->exists) @method('PUT') @endif
        <div class="card-body row g-3">
            <div class="col-md-3"><label class="form-label">Tipo</label><select name="address_type" class="form-select"><option value="billing" @selected(old('address_type', $address->address_type)==='billing')>Facturación</option><option value="shipping" @selected(old('address_type', $address->address_type)==='shipping')>Despacho</option><option value="other" @selected(old('address_type', $address->address_type)==='other')>Otra</option></select></div>
            <div class="col-md-3"><label class="form-label">Etiqueta</label><select name="address_label" class="form-select"><option value="main" @selected(old('address_label', $address->address_label)==='main')>Principal</option><option value="office" @selected(old('address_label', $address->address_label)==='office')>Oficina</option><option value="home" @selected(old('address_label', $address->address_label)==='home')>Casa</option><option value="pickup" @selected(old('address_label', $address->address_label)==='pickup')>Retiro</option></select></div>
            <div class="col-md-3"><label class="form-label">Contacto</label><input name="contact_name" class="form-control" value="{{ old('contact_name', $address->contact_name ?: $customer->display_name) }}" required></div>
            <div class="col-md-3"><label class="form-label">Teléfono</label><input name="phone" class="form-control" value="{{ old('phone', $address->phone ?: $customer->phone) }}"></div>
            @foreach(['country' => 'País', 'region' => 'Región', 'province' => 'Provincia', 'commune' => 'Comuna', 'city' => 'Ciudad', 'street' => 'Calle', 'number' => 'Número', 'apartment' => 'Depto/oficina', 'postal_code' => 'Código postal'] as $field => $label)
                <div class="col-md-{{ in_array($field, ['street']) ? 6 : 3 }}"><label class="form-label">{{ $label }}</label><input name="{{ $field }}" class="form-control" value="{{ old($field, $address->{$field} ?: ($field === 'country' ? 'Chile' : '')) }}" @required(! in_array($field, ['province', 'apartment', 'postal_code']))></div>
            @endforeach
            <div class="col-md-3"><label class="form-label">Latitud</label><input type="number" step="0.0000001" name="latitude" class="form-control" value="{{ old('latitude', $address->latitude) }}"></div>
            <div class="col-md-3"><label class="form-label">Longitud</label><input type="number" step="0.0000001" name="longitude" class="form-control" value="{{ old('longitude', $address->longitude) }}"></div>
            <div class="col-12"><label class="form-label">Referencia</label><textarea name="reference" class="form-control">{{ old('reference', $address->reference) }}</textarea></div>
            <div class="col-12 d-flex gap-3"><label class="form-check"><input type="checkbox" name="is_default" value="1" class="form-check-input" @checked(old('is_default', $address->is_default))> Dirección por defecto</label><label class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" @checked(old('is_active', $address->exists ? $address->is_active : true))> Activa</label></div>
            @if($errors->any())<div class="col-12"><div class="alert alert-danger">{{ $errors->first() }}</div></div>@endif
        </div>
        <div class="card-footer bg-white text-end"><a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-outline-secondary">Cancelar</a> <button class="btn btn-dark">Guardar</button></div>
    </form>
@endsection
