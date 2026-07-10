@extends('layouts.admin')

@section('title', 'Dashboard Ejecutivo')
@section('page-title', 'Dashboard Ejecutivo')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1">Dashboard Ejecutivo</h1>
            <p class="text-secondary mb-0">Resumen diario para ventas, pedidos, clientes, POS e inventario.</p>
        </div>
    </div>

    <x-admin.report-filters :filters="$filters" :show-status="false" />

    <div class="row g-3 mb-4">
        @foreach ($dashboard['cards'] as $card)
            <x-admin.report-card :label="$card['label']" :value="$card['value']" />
        @endforeach
    </div>

    <div class="row g-3">
        @foreach ($dashboard['charts'] as $title => $chart)
            <div class="col-lg-6">
                <x-admin.report-chart :title="$title" :chart="$chart" />
            </div>
        @endforeach
    </div>
@endsection
