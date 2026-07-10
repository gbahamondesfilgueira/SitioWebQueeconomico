<div class="bg-white border rounded p-3 mb-3">
    <h2 class="h6">Cliente</h2>
    @if($cart->customer)
        <div class="mb-2"><strong>{{ $cart->customer->display_name }}</strong><br><small class="text-muted">{{ $cart->customer->email }} · {{ $cart->customer->reward_points }} pts</small></div>
    @else
        <div class="text-muted mb-2">Venta sin cliente identificado.</div>
    @endif
    <form method="POST" action="{{ route('pos.customer.quick-create') }}" class="row g-2">
        @csrf
        <div class="col-6"><input name="first_name" class="form-control form-control-sm" placeholder="Nombre"></div>
        <div class="col-6"><input name="last_name" class="form-control form-control-sm" placeholder="Apellido"></div>
        <div class="col-6"><input name="email" class="form-control form-control-sm" placeholder="Email"></div>
        <div class="col-6"><input name="phone" class="form-control form-control-sm" placeholder="Teléfono"></div>
        <div class="col-6"><input name="rut" class="form-control form-control-sm" placeholder="RUT"></div>
        <div class="col-6 d-grid"><button class="btn btn-sm btn-outline-primary">Crear rápido</button></div>
    </form>
</div>
