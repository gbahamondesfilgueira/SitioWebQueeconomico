@csrf
<div class="row g-3">
    <div class="col-md-4"><label class="form-label">Empresa</label><select name="company_id" class="form-select"><option value="">Sin empresa</option>@foreach($companies as $company)<option value="{{ $company->id }}" @selected(old('company_id', $branch->company_id) == $company->id)>{{ $company->name }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Nombre</label><input name="name" value="{{ old('name', $branch->name) }}" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Código</label><input name="code" value="{{ old('code', $branch->code) }}" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Dirección</label><input name="address" value="{{ old('address', $branch->address) }}" class="form-control"></div>
    <div class="col-md-3"><label class="form-label" for="region">Región</label><x-region-select :value="$branch->region" /></div>
    <div class="col-md-3"><label class="form-label">Comuna</label><input name="commune" value="{{ old('commune', $branch->commune) }}" class="form-control"></div>
    <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email', $branch->email) }}" class="form-control"></div>
    <div class="col-md-6"><label class="form-label">Teléfono</label><input name="phone" value="{{ old('phone', $branch->phone) }}" class="form-control"></div>
    <div class="col-12"><label class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" @checked(old('is_active', $branch->is_active ?? true))> Activa</label></div>
    <div class="col-12"><button class="btn btn-dark">Guardar</button> <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary">Volver</a></div>
</div>
