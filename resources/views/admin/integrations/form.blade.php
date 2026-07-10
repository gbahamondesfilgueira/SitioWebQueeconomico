<form method="POST" action="{{ $action }}" class="card">
@csrf @if($method !== 'POST') @method($method) @endif
<div class="card-body row g-3">
<div class="col-md-6"><label class="form-label">Nombre</label><input name="name" value="{{ old('name', $integration->name) }}" class="form-control" required></div>
<div class="col-md-6"><label class="form-label">Código</label><input name="code" value="{{ old('code', $integration->code) }}" class="form-control" required></div>
<div class="col-md-4"><label class="form-label">Tipo</label><select name="provider_type" class="form-select">@foreach(['ecommerce','marketplace','payment','shipping','accounting','erp','custom_api'] as $type)<option value="{{ $type }}" @selected(old('provider_type',$integration->provider_type)===$type)>{{ $type }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label">Proveedor</label><input name="provider_name" value="{{ old('provider_name', $integration->provider_name) }}" class="form-control" required></div>
<div class="col-md-2"><label class="form-label">Ambiente</label><select name="environment" class="form-select"><option value="sandbox" @selected(old('environment',$integration->environment)==='sandbox')>sandbox</option><option value="production" @selected(old('environment',$integration->environment)==='production')>production</option></select></div>
<div class="col-md-2"><label class="form-label">Estado</label><select name="status" class="form-select">@foreach(['inactive','active','error','suspended'] as $status)<option value="{{ $status }}" @selected(old('status',$integration->status)===$status)>{{ $status }}</option>@endforeach</select></div>
<div class="col-12"><label class="form-label">Base URL</label><input name="base_url" value="{{ old('base_url', $integration->base_url) }}" class="form-control"></div>
<div class="col-md-6"><label class="form-label">API Key</label><input name="api_key" class="form-control"></div>
<div class="col-md-6"><label class="form-label">API Secret</label><input name="api_secret" class="form-control"></div>
<div class="col-md-6"><label class="form-label">Access token</label><input name="access_token" class="form-control"></div>
<div class="col-md-6"><label class="form-label">Webhook secret</label><input name="webhook_secret" class="form-control"></div>
<div class="col-12"><label class="form-label">Descripción</label><textarea name="description" class="form-control">{{ old('description', $integration->description) }}</textarea></div>
<div class="col-12"><label class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" @checked(old('is_active',$integration->is_active))> Activa</label></div>
</div><div class="card-footer text-end"><button class="btn btn-primary">Guardar</button></div></form>
