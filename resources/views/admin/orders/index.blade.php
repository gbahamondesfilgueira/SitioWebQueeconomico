@extends('layouts.admin')

@php use App\Support\StatusLabel; @endphp

@section('title', 'Pedidos')
@section('page-title', 'Pedidos')

@section('content')
    @php
        $orderStatuses = ['pending','confirmed','paid','preparing','ready_to_ship','shipped','delivered','completed','cancelled','refunded'];
        $paymentStatuses = ['pending','paid','partially_paid','failed','refunded'];
    @endphp

    <form class="card border-0 shadow-sm mb-3">
        <div class="card-body row g-2">
            <div class="col-md-2">
                <input name="order_number" class="form-control" placeholder="Pedido" value="{{ request('order_number') }}">
            </div>
            <div class="col-md-2">
                <input name="customer" class="form-control" placeholder="Cliente" value="{{ request('customer') }}">
            </div>
            <div class="col-md-2">
                <select name="order_status" class="form-select">
                    <option value="">Estado pedido</option>
                    @foreach($orderStatuses as $status)
                        <option value="{{ $status }}" @selected(request('order_status') === $status)>{{ StatusLabel::order($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="payment_status" class="form-select">
                    <option value="">Estado pago</option>
                    @foreach($paymentStatuses as $status)
                        <option value="{{ $status }}" @selected(request('payment_status') === $status)>{{ StatusLabel::payment($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-dark w-100">Filtrar</button>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Numero</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Pedido</th>
                        <th>Pago</th>
                        <th>Preparacion</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>
                                {{ $order->customer_name }}
                                <div class="small text-secondary">{{ $order->customer_email }}</div>
                            </td>
                            <td>${{ number_format((float) $order->grand_total, 0, ',', '.') }}</td>
                            <td><span class="badge text-bg-light">{{ StatusLabel::order($order->order_status) }}</span></td>
                            <td><span class="badge text-bg-light">{{ StatusLabel::payment($order->payment_status) }}</span></td>
                            <td><span class="badge text-bg-light">{{ StatusLabel::fulfillment($order->fulfillment_status) }}</span></td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-dark">Ver</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-secondary py-4">Sin pedidos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">{{ $orders->links() }}</div>
    </div>
@endsection
