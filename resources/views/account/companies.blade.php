@extends('layouts.account')

@section('title', 'Mis Empresas')

@section('content')
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white fw-semibold">Agregar empresa para facturacion</div>
        <form method="POST" action="{{ route('account.companies.store') }}" class="card-body row g-3">
            @csrf
            <div class="col-md-6"><label class="form-label">Razon social</label><input name="company_name" class="form-control" value="{{ old('company_name') }}" required></div>
            <div class="col-md-3"><label class="form-label">RUT</label><input name="rut" class="form-control" value="{{ old('rut') }}" required></div>
            <div class="col-md-3"><label class="form-label">Giro</label><input name="business_activity" class="form-control" value="{{ old('business_activity') }}" required></div>
            <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" required></div>
            <div class="col-md-4"><label class="form-label">Telefono</label><input name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}" required></div>
            <div class="col-md-4"><label class="form-label">Email facturacion</label><input type="email" name="billing_email" class="form-control" value="{{ old('billing_email') }}"></div>
            <div class="col-md-6"><label class="form-label">Sitio web</label><input type="url" name="website" class="form-control" value="{{ old('website') }}"></div>
            <div class="col-md-3"><label class="form-label">Condiciones de pago</label><input name="payment_terms" class="form-control" value="{{ old('payment_terms') }}"></div>
            <div class="col-md-3"><label class="form-label">Credito</label><input type="number" min="0" step="1" name="credit_limit" class="form-control" value="{{ old('credit_limit') }}"></div>
            <div class="col-12"><button class="btn btn-dark">Guardar empresa</button></div>
        </form>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold">Mis Empresas</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <tbody>
                    @forelse($customer->companies as $company)
                        <tr>
                            <td>{{ $company->company_name }}</td>
                            <td>{{ $company->rut }}</td>
                            <td>{{ $company->billing_email ?: $company->email }}</td>
                        </tr>
                    @empty
                        <tr><td class="text-secondary">Todavia no tienes empresas registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
