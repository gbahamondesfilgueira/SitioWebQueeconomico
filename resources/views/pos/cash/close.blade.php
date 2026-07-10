@extends('layouts.pos')
@section('title', 'Cerrar caja')
@section('content')
<div class="row g-3">
    <div class="col-lg-7">
        <div class="bg-white border rounded p-3">
            <h1 class="h4">Resumen para cierre</h1>
            @include('pos.cash.partials.totals')
        </div>
    </div>
    <div class="col-lg-5">
        <form method="POST" action="{{ route('pos.cash.close.store') }}" class="bg-white border rounded p-3">
            @csrf
            <h2 class="h5">Conteo final</h2>
            <label class="form-label">Efectivo contado</label>
            <input name="counted_cash_amount" type="number" min="0" step="1" value="{{ (int) $summary['cashExpected'] }}" class="form-control form-control-lg mb-3" required>
            <label class="form-label">Observaciones</label>
            <textarea name="notes" class="form-control mb-3"></textarea>
            <button class="btn btn-danger btn-lg w-100">Cerrar caja</button>
        </form>
    </div>
</div>
@endsection
