@extends('layouts.account')

@section('title', 'Mis compras')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Mis compras</h1>
        <a href="{{ route('store.shop') }}" class="btn btn-outline-dark btn-sm">Ir a la tienda</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Numero</th>
                        <th>Total</th>
                        <th>Pedido</th>
                        <th>Pago</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>${{ number_format((float) $order->grand_total, 0, ',', '.') }}</td>
                            <td>{{ $order->order_status }}</td>
                            <td>{{ $order->payment_status }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td><a href="{{ route('store.account.orders.show', $order->order_number) }}" class="btn btn-sm btn-outline-dark">Ver</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <p class="text-secondary mb-3">No tienes compras registradas.</p>
                                <a href="{{ route('store.shop') }}" class="btn btn-dark">Comprar en la tienda</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $orders->links() }}</div>
@endsection
