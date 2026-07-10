<div class="bg-white border rounded p-3 mb-3">
    <h2 class="h6">Pagos</h2>
    <form method="POST" action="{{ route('pos.payment.add') }}" class="row g-2 mb-2">
        @csrf
        <div class="col-6"><select name="payment_method_id" class="form-select form-select-sm">@foreach($paymentMethods as $method)<option value="{{ $method->id }}">{{ $method->name }}</option>@endforeach</select></div>
        <div class="col-6"><input name="amount" type="number" min="1" step="1" value="{{ (int) $summary['grandTotal'] }}" class="form-control form-control-sm"></div>
        <div class="col-8"><input name="reference" class="form-control form-control-sm" placeholder="Referencia opcional"></div>
        <div class="col-4 d-grid"><button class="btn btn-sm btn-outline-primary">Agregar</button></div>
    </form>
    @foreach($cart->payments as $payment)
        <form method="POST" action="{{ route('pos.payment.remove') }}" class="d-flex justify-content-between border-top py-1">
            @csrf
            <input type="hidden" name="payment_id" value="{{ $payment->id }}">
            <span>{{ $payment->method->name }}: ${{ number_format($payment->amount, 0, ',', '.') }}</span>
            <button class="btn btn-sm btn-link text-danger">Quitar</button>
        </form>
    @endforeach
</div>
