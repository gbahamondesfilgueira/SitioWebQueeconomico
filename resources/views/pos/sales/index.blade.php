@extends('layouts.pos')
@section('title', 'Ventas POS')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4">Ventas POS</h1>
    <a class="btn btn-primary" href="{{ route('pos.sale.create') }}">Nueva venta</a>
</div>
<p class="text-muted">Las ventas POS quedan registradas en el módulo de pedidos con canal POS.</p>
@endsection
