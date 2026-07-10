@php($selectedTags = old('tag_ids', $customer->exists ? $customer->tags->pluck('id')->all() : []))
<div class="row g-3">
    <div class="col-md-3">
        <label class="form-label">Tipo de cliente</label>
        <select name="customer_type" class="form-select">
            <option value="individual" @selected(old('customer_type', $customer->customer_type) === 'individual')>Particular</option>
            <option value="company" @selected(old('customer_type', $customer->customer_type) === 'company')>Empresa</option>
        </select>
    </div>
    <div class="col-md-3"><label class="form-label">Nombre</label><input name="first_name" class="form-control" value="{{ old('first_name', $customer->first_name) }}" required></div>
    <div class="col-md-3"><label class="form-label">Apellido</label><input name="last_name" class="form-control" value="{{ old('last_name', $customer->last_name) }}" required></div>
    <div class="col-md-3"><label class="form-label">RUT</label><input name="rut" class="form-control" value="{{ old('rut', $customer->rut) }}"></div>
    <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" required></div>
    <div class="col-md-4"><label class="form-label">Teléfono</label><input name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}"></div>
    <div class="col-md-4"><label class="form-label">Móvil</label><input name="mobile" class="form-control" value="{{ old('mobile', $customer->mobile) }}"></div>
    <div class="col-md-4"><label class="form-label">Empresa</label><input name="company_name" class="form-control" value="{{ old('company_name', $customer->company_name) }}"></div>
    <div class="col-md-4"><label class="form-label">Giro</label><input name="business_activity" class="form-control" value="{{ old('business_activity', $customer->business_activity) }}"></div>
    <div class="col-md-2"><label class="form-label">Nacimiento</label><input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', optional($customer->birth_date)->format('Y-m-d')) }}"></div>
    <div class="col-md-2"><label class="form-label">Género</label><input name="gender" class="form-control" value="{{ old('gender', $customer->gender) }}"></div>
    <div class="col-md-4">
        <label class="form-label">Lista de precios preferida</label>
        <select name="preferred_price_list_id" class="form-select">
            <option value="">Sin preferencia</option>
            @foreach($priceLists as $list)
                <option value="{{ $list->id }}" @selected((int) old('preferred_price_list_id', $customer->preferred_price_list_id) === $list->id)>{{ $list->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Etiquetas</label>
        <select name="tag_ids[]" class="form-select" multiple>
            @foreach($customerTags as $tag)
                <option value="{{ $tag->id }}" @selected(in_array($tag->id, $selectedTags))>{{ $tag->name }}</option>
            @endforeach
        </select>
    </div>
    @unless($customer->exists)
        <div class="col-md-4"><label class="form-label">Contraseña inicial</label><input type="password" name="password" class="form-control"></div>
        <div class="col-md-4"><label class="form-label">Confirmar contraseña</label><input type="password" name="password_confirmation" class="form-control"></div>
    @endunless
    <div class="col-12"><label class="form-label">Notas internas</label><textarea name="notes" class="form-control" rows="3">{{ old('notes', $customer->notes) }}</textarea></div>
    <div class="col-12">
        <div class="d-flex flex-wrap gap-3">
            @foreach(['is_active' => 'Activo', 'newsletter' => 'Newsletter', 'accept_promotions' => 'Promociones', 'accept_sms' => 'SMS', 'accept_whatsapp' => 'WhatsApp', 'accept_email_marketing' => 'Email marketing', 'accept_cookies' => 'Cookies'] as $field => $label)
                <label class="form-check"><input type="checkbox" name="{{ $field }}" value="1" class="form-check-input" @checked(old($field, $customer->exists ? $customer->{$field} : $field === 'is_active'))> {{ $label }}</label>
            @endforeach
        </div>
    </div>
</div>
@if ($errors->any())
    <div class="alert alert-danger mt-3 mb-0">{{ $errors->first() }}</div>
@endif
