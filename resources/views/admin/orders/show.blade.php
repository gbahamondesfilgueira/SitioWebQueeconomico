@extends('layouts.admin')

@php use App\Support\StatusLabel; @endphp

@section('title', 'Pedido '.$order->order_number)
@section('page-title', 'Pedido '.$order->order_number)

@section('content')
    @php
        $orderStatuses = ['pending','confirmed','paid','preparing','ready_to_ship','shipped','delivered','completed','cancelled','refunded'];
        $paymentStatuses = ['pending','paid','partially_paid','failed','refunded'];
    @endphp

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Items</div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        {{ (int) $item->quantity }} x {{ $item->product_name }} {{ $item->variant_name }}
                                        @if($item->packComponents->isNotEmpty())
                                            <div class="small text-secondary">Componentes: {{ $item->packComponents->pluck('product_name')->implode(', ') }}</div>
                                        @endif
                                    </td>
                                    <td class="text-end">${{ number_format((float) $item->line_total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Historial</div>
                <div class="card-body">
                    @forelse($order->histories as $history)
                        <div class="small border-bottom py-2">
                            {{ $history->created_at?->format('d/m/Y H:i') }}
                            · {{ StatusLabel::generic($history->status_type) }}
                            · {{ StatusLabel::generic($history->old_status) }} -> {{ StatusLabel::generic($history->new_status) }}
                            · {{ $history->user?->name ?? 'Sistema' }}
                        </div>
                    @empty
                        <p class="text-secondary mb-0">Sin historial registrado.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h2 class="h6">Cliente</h2>
                    <p>{{ $order->customer_name }}<br>{{ $order->customer_email }}<br>{{ $order->customer_phone }}</p>
                    <h2 class="h6">Total</h2>
                    <div class="fs-4 fw-bold">${{ number_format((float) $order->grand_total, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mb-2">
                        @csrf
                        @method('PATCH')
                        <label class="form-label">Estado pedido</label>
                        <select name="order_status" class="form-select mb-2">
                            @foreach($orderStatuses as $status)
                                <option value="{{ $status }}" @selected($order->order_status === $status)>{{ StatusLabel::order($status) }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-dark">Actualizar</button>
                    </form>

                    <form method="POST" action="{{ route('admin.orders.payment-status', $order) }}">
                        @csrf
                        @method('PATCH')
                        <label class="form-label">Estado pago</label>
                        <select name="payment_status" class="form-select mb-2">
                            @foreach($paymentStatuses as $status)
                                <option value="{{ $status }}" @selected($order->payment_status === $status)>{{ StatusLabel::payment($status) }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-dark">Actualizar pago</button>
                    </form>
                </div>
            </div>

            <a href="{{ route('admin.orders.fulfillment', $order) }}" class="btn btn-outline-primary w-100 mb-2">Preparar pedido</a>
            <form method="POST" action="{{ route('admin.shipping.labels.generate', $order) }}" class="mb-2">
                @csrf
                <button class="btn btn-outline-success w-100">Generar etiqueta</button>
            </form>
            <a href="{{ route('admin.orders.cancel', $order) }}" class="btn btn-outline-danger w-100">Cancelar pedido</a>
        </div>
    </div>
@endsection
