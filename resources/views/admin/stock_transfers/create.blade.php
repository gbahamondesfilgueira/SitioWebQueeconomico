@extends('layouts.admin')

@section('title', 'Crear transferencia')
@section('page-title', 'Crear transferencia')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.stock-transfers.store') }}" class="row g-3">
                @csrf

                <div class="col-md-6">
                    <label class="form-label">Bodega origen</label>
                    <select class="form-select" name="origin_warehouse_id" required>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Bodega destino</label>
                    <select class="form-select" name="destination_warehouse_id" required>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Bodega / ubicacion de origen</label>
                    <select class="form-select" name="origin_location_id">
                        <option value="">Sin ubicacion especifica</option>
                        @foreach($warehouses as $warehouse)
                            @foreach($warehouse->locations as $location)
                                <option value="{{ $location->id }}">{{ $warehouse->code }} / {{ $location->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                    <div class="form-text">Indica desde que zona fisica saldra el stock.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Bodega / ubicacion de destino</label>
                    <select class="form-select" name="destination_location_id">
                        <option value="">Sin ubicacion especifica</option>
                        @foreach($warehouses as $warehouse)
                            @foreach($warehouse->locations as $location)
                                <option value="{{ $location->id }}">{{ $warehouse->code }} / {{ $location->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                    <div class="form-text">Indica donde quedara fisicamente el stock recibido.</div>
                </div>

                <div class="col-12"><h2 class="h6">Item principal</h2></div>

                <div class="col-md-5">
                    <label class="form-label">Producto</label>
                    <select class="form-select" name="items[0][product_id]" required>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label">Variante</label>
                    <select class="form-select" name="items[0][product_variant_id]">
                        <option value="">Producto simple</option>
                        @foreach($variants as $variant)
                            <option value="{{ $variant->id }}">{{ $variant->product->name }} / {{ $variant->sku }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Cantidad</label>
                    <input class="form-control" name="items[0][quantity]" type="number" min="1" step="1" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Notas</label>
                    <textarea class="form-control" name="notes"></textarea>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary">Crear transferencia</button>
                </div>
            </form>
        </div>
    </div>
@endsection
