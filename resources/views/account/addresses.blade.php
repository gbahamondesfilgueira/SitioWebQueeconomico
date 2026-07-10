@extends('layouts.account')

@section('title', 'Mis Direcciones')

@section('content')
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white fw-semibold">Agregar direccion</div>
        <form method="POST" action="{{ route('account.addresses.store') }}" class="card-body row g-3">
            @csrf
            <div class="col-md-3"><label class="form-label">Tipo</label><select name="address_type" class="form-select"><option value="shipping">Despacho</option><option value="billing">Facturacion</option><option value="other">Otra</option></select></div>
            <div class="col-md-3"><label class="form-label">Etiqueta</label><select name="address_label" class="form-select"><option value="main">Principal</option><option value="home">Casa</option><option value="office">Oficina</option><option value="pickup">Retiro</option></select></div>
            <div class="col-md-3"><label class="form-label">Contacto</label><input name="contact_name" class="form-control" value="{{ old('contact_name', $customer->display_name) }}" required></div>
            <div class="col-md-3"><label class="form-label">Telefono</label><input name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}" required></div>
            <div class="col-md-3"><label class="form-label">Pais</label><input name="country" class="form-control" value="{{ old('country', 'Chile') }}" required></div>
            <div class="col-md-3"><label class="form-label">Region</label><input name="region" class="form-control" value="{{ old('region') }}" required></div>
            <div class="col-md-3"><label class="form-label">Provincia</label><input name="province" class="form-control" value="{{ old('province') }}"></div>
            <div class="col-md-3"><label class="form-label">Comuna</label><input name="commune" class="form-control" value="{{ old('commune') }}" required></div>
            <div class="col-md-4"><label class="form-label">Ciudad</label><input name="city" class="form-control" value="{{ old('city') }}" required></div>
            <div class="col-md-5"><label class="form-label">Calle</label><input name="street" class="form-control" value="{{ old('street') }}" required></div>
            <div class="col-md-3"><label class="form-label">Numero</label><input name="number" class="form-control" value="{{ old('number') }}" required></div>
            <div class="col-md-4"><label class="form-label">Depto/Casa</label><input name="apartment" class="form-control" value="{{ old('apartment') }}"></div>
            <div class="col-md-4"><label class="form-label">Codigo postal</label><input name="postal_code" class="form-control" value="{{ old('postal_code') }}"></div>
            <div class="col-md-4 d-flex align-items-end"><label class="form-check mb-2"><input type="checkbox" name="is_default" value="1" class="form-check-input" checked> Usar por defecto</label></div>
            <div class="col-12"><label class="form-label">Referencia</label><textarea name="reference" class="form-control">{{ old('reference') }}</textarea></div>
            <div class="col-12"><button class="btn btn-dark">Guardar direccion</button></div>
        </form>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold">Mis Direcciones</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <tbody>
                    @forelse($customer->addresses as $address)
                        <tr>
                            <td>{{ $address->address_type }} · {{ $address->address_label }}</td>
                            <td>{{ $address->street }} {{ $address->number }}, {{ $address->commune }}</td>
                            <td>{{ $address->is_default ? 'Por defecto' : '' }}</td>
                        </tr>
                    @empty
                        <tr><td class="text-secondary">Todavia no tienes direcciones registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
