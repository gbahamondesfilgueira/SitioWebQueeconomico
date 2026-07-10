@extends('layouts.account')

@section('title', 'Mi Perfil')

@section('content')
    <form method="POST" action="{{ route('account.profile.update') }}" class="card border-0 shadow-sm">
        @csrf
        @method('PUT')
        <div class="card-header bg-white fw-semibold">Mi Perfil</div>
        <div class="card-body row g-3">
            <div class="col-md-6"><label class="form-label">Nombre</label><input name="first_name" class="form-control" value="{{ old('first_name', $customer->first_name) }}" required></div>
            <div class="col-md-6"><label class="form-label">Apellido</label><input name="last_name" class="form-control" value="{{ old('last_name', $customer->last_name) }}" required></div>
            <div class="col-md-4"><label class="form-label">Teléfono</label><input name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}"></div>
            <div class="col-md-4"><label class="form-label">Móvil</label><input name="mobile" class="form-control" value="{{ old('mobile', $customer->mobile) }}"></div>
            <div class="col-md-4"><label class="form-label">Nacimiento</label><input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', optional($customer->birth_date)->format('Y-m-d')) }}"></div>
            <div class="col-md-4"><label class="form-label">Género</label><input name="gender" class="form-control" value="{{ old('gender', $customer->gender) }}"></div>
            <div class="col-12 d-flex flex-wrap gap-3">
                @foreach(['newsletter' => 'Newsletter', 'accept_promotions' => 'Promociones', 'accept_sms' => 'SMS', 'accept_whatsapp' => 'WhatsApp', 'accept_email_marketing' => 'Email marketing', 'accept_cookies' => 'Cookies'] as $field => $label)
                    <label class="form-check"><input type="checkbox" name="{{ $field }}" value="1" class="form-check-input" @checked(old($field, $customer->{$field}))> {{ $label }}</label>
                @endforeach
            </div>
            @if($errors->any())<div class="col-12"><div class="alert alert-danger">{{ $errors->first() }}</div></div>@endif
        </div>
        <div class="card-footer bg-white text-end"><button class="btn btn-dark">Guardar</button></div>
    </form>
@endsection
