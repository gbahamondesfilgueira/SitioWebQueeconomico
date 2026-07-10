<form method="POST" action="{{ $action }}" class="card">@csrf @if($method!=='POST') @method($method) @endif
<div class="card-body row g-3">
@if($method==='POST')
<div class="col-md-6"><label class="form-label">Integración</label><select name="integration_id" class="form-select">@foreach($integrations as $integration)<option value="{{ $integration->id }}">{{ $integration->name }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label">Producto</label><select name="product_id" class="form-select">@foreach($products as $product)<option value="{{ $product->id }}">{{ $product->name }}</option>@endforeach</select></div>
@endif
<div class="col-md-6"><label class="form-label">ID producto externo</label><input name="external_product_id" value="{{ old('external_product_id',$mapping->external_product_id ?? '') }}" class="form-control" required></div>
<div class="col-md-6"><label class="form-label">ID variante externa</label><input name="external_variant_id" value="{{ old('external_variant_id',$mapping->external_variant_id ?? '') }}" class="form-control"></div>
<div class="col-md-6"><label class="form-label">SKU externo</label><input name="external_sku" value="{{ old('external_sku',$mapping->external_sku ?? '') }}" class="form-control"></div>
<div class="col-md-3"><label class="form-check mt-4"><input type="checkbox" name="sync_stock" value="1" class="form-check-input" @checked(old('sync_stock',$mapping->sync_stock ?? true))> Sync stock</label></div>
<div class="col-md-3"><label class="form-check mt-4"><input type="checkbox" name="sync_price" value="1" class="form-check-input" @checked(old('sync_price',$mapping->sync_price ?? true))> Sync precio</label></div>
</div><div class="card-footer text-end"><button class="btn btn-primary">Guardar</button></div></form>
