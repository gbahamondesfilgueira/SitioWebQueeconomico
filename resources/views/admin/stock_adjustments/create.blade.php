@extends('layouts.admin')
@section('title','Crear ajuste')
@section('page-title','Crear ajuste')
@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.stock-adjustments.store') }}" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label class="form-label">Bodega</label>
                <select class="form-select" name="warehouse_id" required>
                    @foreach($warehouses as $w)<option value="{{ $w->id }}">{{ $w->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Bodega / ubicacion de stock</label>
                <select class="form-select" name="warehouse_location_id">
                    <option value="">Sin ubicacion especifica</option>
                    @foreach($warehouses as $w)
                        @foreach($w->locations as $l)<option value="{{ $l->id }}">{{ $w->code }} / {{ $l->name }}</option>@endforeach
                    @endforeach
                </select>
                <div class="form-text">La ubicacion permite indicar en que bodega o zona fisica se encuentra el stock del producto. El stock disponible para venta queda asignado a una ubicacion activa.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Producto</label>
                <select class="form-select" name="product_id" required>
                    @foreach($products as $p)<option value="{{ $p->id }}" @selected(old('product_id', $selectedProductId ?? null) == $p->id)>{{ $p->name }} @if($p->sku) / {{ $p->sku }} @endif</option>@endforeach
                </select>
                <div class="form-text">Para cargar stock a un producto recien creado, crea este ajuste como aumento y luego apruebalo.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Variante</label>
                <select class="form-select" name="product_variant_id">
                    <option value="">Producto simple</option>
                    @foreach($variants as $v)<option value="{{ $v->id }}" @selected(old('product_variant_id', $selectedVariantId ?? null) == $v->id)>{{ $v->product->name }} / {{ $v->sku ?: $v->name }}</option>@endforeach
                </select>
                <div class="form-text">Si el producto es variable, selecciona la variante exacta antes de guardar.</div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tipo</label>
                <select class="form-select" name="adjustment_type"><option value="increase">Aumentar</option><option value="decrease">Disminuir</option></select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Cantidad</label>
                <input class="form-control" name="quantity" type="number" min="1" step="1" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Motivo</label>
                <select class="form-select" name="reason">
                    @foreach(['correction' => 'Correccion','damage' => 'Merma/daño','loss' => 'Perdida','inventory_count' => 'Conteo inventario','return' => 'Devolucion','other' => 'Otro'] as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12"><label class="form-label">Notas</label><textarea class="form-control" name="notes"></textarea></div>
            <div class="col-12 d-flex justify-content-end"><button class="btn btn-primary">Guardar pendiente</button></div>
        </form>
    </div>
</div>
@endsection
