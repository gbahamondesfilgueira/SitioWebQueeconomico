<form method="POST" action="{{ route('store.checkout.customer') }}" class="card border-0 shadow-sm">
    @csrf
    <div class="card-header bg-white fw-semibold">Identificación cliente</div>
    <div class="card-body row g-3">
        <div class="col-md-6"><label class="form-label">Nombre</label><input name="first_name" class="form-control" value="{{ old('first_name', $customer?->first_name ?? session('checkout.customer.first_name')) }}" required></div>
        <div class="col-md-6"><label class="form-label">Apellido</label><input name="last_name" class="form-control" value="{{ old('last_name', $customer?->last_name ?? session('checkout.customer.last_name')) }}" required></div>
        <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $customer?->email ?? session('checkout.customer.email')) }}" required></div>
        <div class="col-md-3"><label class="form-label">Teléfono</label><input name="phone" class="form-control" value="{{ old('phone', $customer?->phone ?? session('checkout.customer.phone')) }}" required></div>
        <div class="col-md-3"><label class="form-label">RUT</label><input name="rut" class="form-control" value="{{ old('rut', $customer?->rut ?? session('checkout.customer.rut')) }}"></div>
    </div>
    <div class="card-footer bg-white text-end"><button class="btn btn-dark">Continuar</button></div>
</form>
