@props(['type' => 'shipping', 'savedAddresses' => collect()])

@if($savedAddresses->isNotEmpty())
    <label class="form-label">Usar direccion guardada</label>
    <select name="customer_address_id" class="form-select mb-3" data-address-select>
        <option value="">Ingresar nueva direccion</option>
        @foreach($savedAddresses as $address)
            <option
                value="{{ $address->id }}"
                data-address='@json([
                    "contact_name" => $address->contact_name,
                    "phone" => $address->phone,
                    "email" => $address->customerProfile?->email,
                    "country" => $address->country,
                    "region" => $address->region,
                    "commune" => $address->commune,
                    "city" => $address->city,
                    "street" => $address->street,
                    "number" => $address->number,
                    "apartment" => $address->apartment,
                    "postal_code" => $address->postal_code,
                    "reference" => $address->reference,
                ])'
            >
                {{ $address->address_type }} · {{ $address->street }} {{ $address->number }}, {{ $address->commune }}
            </option>
        @endforeach
    </select>
@endif

<div class="row g-3" data-address-fields>
    <div class="col-md-6"><label class="form-label">Nombre contacto</label><input name="contact_name" class="form-control" value="{{ old('contact_name') }}"></div>
    <div class="col-md-3"><label class="form-label">Telefono</label><input name="phone" class="form-control" value="{{ old('phone') }}"></div>
    <div class="col-md-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email') }}"></div>
    <div class="col-md-3"><label class="form-label">Pais</label><input name="country" class="form-control" value="{{ old('country', 'Chile') }}"></div>
    <div class="col-md-3"><label class="form-label">Region</label><input name="region" class="form-control" value="{{ old('region') }}"></div>
    <div class="col-md-3"><label class="form-label">Comuna</label><input name="commune" class="form-control" value="{{ old('commune') }}"></div>
    <div class="col-md-3"><label class="form-label">Ciudad</label><input name="city" class="form-control" value="{{ old('city') }}"></div>
    <div class="col-md-6"><label class="form-label">Calle</label><input name="street" class="form-control" value="{{ old('street') }}"></div>
    <div class="col-md-2"><label class="form-label">Numero</label><input name="number" class="form-control" value="{{ old('number') }}"></div>
    <div class="col-md-2"><label class="form-label">Depto</label><input name="apartment" class="form-control" value="{{ old('apartment') }}"></div>
    <div class="col-md-2"><label class="form-label">Codigo postal</label><input name="postal_code" class="form-control" value="{{ old('postal_code') }}"></div>
    <div class="col-12"><label class="form-label">Referencia</label><textarea name="reference" class="form-control">{{ old('reference') }}</textarea></div>
</div>
