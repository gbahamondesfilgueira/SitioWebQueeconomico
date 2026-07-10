<form method="POST" action="{{ $action }}" class="card">
    @csrf
    @if($method !== 'POST') @method($method) @endif
    <div class="card-body row g-3">
        <div class="col-md-6"><label class="form-label">Nombre</label><input name="name" value="{{ old('name', $terminal?->name) }}" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label">Código</label><input name="code" value="{{ old('code', $terminal?->code) }}" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label">Bodega</label><select name="warehouse_id" class="form-select" required>@foreach($warehouses as $warehouse)<option value="{{ $warehouse->id }}" @selected(old('warehouse_id', $terminal?->warehouse_id) == $warehouse->id)>{{ $warehouse->name }}</option>@endforeach</select></div>
        <div class="col-md-6"><label class="form-label">Ubicación</label><select name="location_id" class="form-select"><option value="">Sin ubicación específica</option>@foreach($locations as $location)<option value="{{ $location->id }}" @selected(old('location_id', $terminal?->location_id) == $location->id)>{{ $location->name }}</option>@endforeach</select></div>
        <div class="col-12"><label class="form-label">Descripción</label><textarea name="description" class="form-control">{{ old('description', $terminal?->description) }}</textarea></div>
        <div class="col-12"><label class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" @checked(old('is_active', $terminal?->is_active ?? true))> Activo</label></div>
    </div>
    <div class="card-footer text-end"><button class="btn btn-primary">Guardar</button></div>
</form>
