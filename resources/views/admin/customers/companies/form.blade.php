@extends('layouts.admin')

@section('title', $company->exists ? 'Editar empresa' : 'Nueva empresa')
@section('page-title', $company->exists ? 'Editar empresa' : 'Nueva empresa')

@section('content')
    <form method="POST" action="{{ $company->exists ? route('admin.customers.companies.update', [$customer, $company]) : route('admin.customers.companies.store', $customer) }}" class="card border-0 shadow-sm">
        @csrf
        @if($company->exists) @method('PUT') @endif
        <div class="card-body row g-3">
            <div class="col-md-6"><label class="form-label">Razón social</label><input name="company_name" class="form-control" value="{{ old('company_name', $company->company_name) }}" required></div>
            <div class="col-md-3"><label class="form-label">RUT</label><input name="rut" class="form-control" value="{{ old('rut', $company->rut) }}" required></div>
            <div class="col-md-3"><label class="form-label">Giro</label><input name="business_activity" class="form-control" value="{{ old('business_activity', $company->business_activity) }}" required></div>
            <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $company->email) }}" required></div>
            <div class="col-md-4"><label class="form-label">Teléfono</label><input name="phone" class="form-control" value="{{ old('phone', $company->phone) }}"></div>
            <div class="col-md-4"><label class="form-label">Sitio web</label><input type="url" name="website" class="form-control" value="{{ old('website', $company->website) }}"></div>
            <div class="col-md-4"><label class="form-label">Email facturación</label><input type="email" name="billing_email" class="form-control" value="{{ old('billing_email', $company->billing_email) }}"></div>
            <div class="col-md-4"><label class="form-label">Condición de pago</label><input name="payment_terms" class="form-control" value="{{ old('payment_terms', $company->payment_terms) }}"></div>
            <div class="col-md-4"><label class="form-label">Límite crédito</label><input type="number" step="0.01" name="credit_limit" class="form-control" value="{{ old('credit_limit', $company->credit_limit) }}"></div>
            <div class="col-12"><label class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" @checked(old('is_active', $company->exists ? $company->is_active : true))> Activa</label></div>
            @if($errors->any())<div class="col-12"><div class="alert alert-danger">{{ $errors->first() }}</div></div>@endif
        </div>
        <div class="card-footer bg-white text-end"><a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-outline-secondary">Cancelar</a> <button class="btn btn-dark">Guardar</button></div>
    </form>
@endsection
