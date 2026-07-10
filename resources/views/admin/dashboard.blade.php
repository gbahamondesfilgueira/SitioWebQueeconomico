@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    @if($newOrders > 0)
        <div class="alert alert-warning border-0 shadow-sm d-flex justify-content-between align-items-center">
            <div><strong>{{ $newOrders }} pedido(s) nuevo(s)</strong> en las ultimas 24 horas.</div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-dark">Ver pedidos</a>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-secondary small">Ventas hoy</div><div class="fs-4 fw-bold">${{ number_format((float) $todaySales, 0, ',', '.') }}</div></div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-secondary small">Ventas mes</div><div class="fs-4 fw-bold">${{ number_format((float) $monthSales, 0, ',', '.') }}</div></div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-secondary small">Pedidos pendientes</div><div class="fs-4 fw-bold text-warning">{{ $pendingOrders }}</div></div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-secondary small">Stock bajo minimo</div><div class="fs-4 fw-bold text-danger">{{ $lowStockProducts }}</div></div></div></div>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong>Ultimos pedidos</strong>
                    <a href="{{ route('admin.orders.index') }}" class="small">Ver todos</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead><tr><th>Pedido</th><th>Cliente</th><th>Total</th><th>Estado</th><th>Fecha</th></tr></thead>
                        <tbody>
                            @forelse($latestOrders as $order)
                                <tr>
                                    <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>${{ number_format((float) $order->grand_total, 0, ',', '.') }}</td>
                                    <td><span class="badge text-bg-light">{{ $order->order_status }}</span></td>
                                    <td class="small text-secondary">{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-secondary py-4">Sin pedidos todavia.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Estado rapido</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6"><div class="text-secondary small">Clientes activos</div><div class="fs-5 fw-bold">{{ $activeCustomers }}</div></div>
                        <div class="col-6"><div class="text-secondary small">Productos con stock</div><div class="fs-5 fw-bold">{{ $stockedProducts }}</div></div>
                        <div class="col-6"><div class="text-secondary small">Bodegas activas</div><div class="fs-5 fw-bold">{{ $activeWarehouses }}</div></div>
                        <div class="col-6"><div class="text-secondary small">Ajustes pendientes</div><div class="fs-5 fw-bold">{{ $pendingAdjustments }}</div></div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Ultimos eventos</div>
                <div class="list-group list-group-flush">
                    @forelse ($auditLogs->take(5) as $log)
                        <div class="list-group-item">
                            <div class="small text-secondary">{{ $log->created_at?->format('d/m/Y H:i') }} · {{ $log->module }}</div>
                            <div>{{ $log->description }}</div>
                        </div>
                    @empty
                        <div class="list-group-item text-secondary">Sin eventos registrados.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
