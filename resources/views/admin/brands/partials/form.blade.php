<div class="col-md-6">
    <label class="form-label" for="name">Nombre</label>
    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $brand->name) }}" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-6">
    <label class="form-label" for="slug">Slug</label>
    <input class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $brand->slug) }}" placeholder="Se genera automáticamente">
    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12">
    <label class="form-label" for="description">Descripción</label>
    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $brand->description) }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-6">
    <label class="form-label" for="website">Sitio web</label>
    <input class="form-control @error('website') is-invalid @enderror" id="website" name="website" type="url" value="{{ old('website', $brand->website) }}" placeholder="https://">
    @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-6">
    <label class="form-label" for="logo">Logo</label>
    <input class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" type="file" accept="image/*">
    @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    @if ($brand->logo_path)
        <div class="small text-secondary mt-1">Logo actual: {{ $brand->logo_path }}</div>
    @endif
</div>

<div class="col-12">
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', $brand->exists ? $brand->is_active : true))>
        <label class="form-check-label" for="is_active">Activo</label>
    </div>
</div>

<div class="col-12 d-flex justify-content-end gap-2">
    <a class="btn btn-outline-secondary" href="{{ route('admin.brands.index') }}">Cancelar</a>
    <button class="btn btn-primary" type="submit">Guardar marca</button>
</div>
