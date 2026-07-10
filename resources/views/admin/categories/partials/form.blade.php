<div class="col-md-6">
    <label class="form-label" for="name">Nombre</label>
    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-6">
    <label class="form-label" for="slug">Slug</label>
    <input class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $category->slug) }}" placeholder="Se genera automáticamente">
    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-6">
    <label class="form-label" for="parent_id">Categoría padre</label>
    <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
        <option value="">Sin padre</option>
        @foreach ($parents as $parent)
            <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{ $parent->name }}</option>
        @endforeach
    </select>
    @error('parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-6">
    <label class="form-label" for="sort_order">Orden</label>
    <input class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $category->sort_order ?? 0) }}" required>
    @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12">
    <label class="form-label" for="description">Descripción</label>
    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-6">
    <label class="form-label" for="image">Imagen</label>
    <input class="form-control @error('image') is-invalid @enderror" id="image" name="image" type="file" accept="image/*">
    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
    @if ($category->image_path)
        <div class="small text-secondary mt-1">Imagen actual: {{ $category->image_path }}</div>
    @endif
</div>

<div class="col-12">
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', $category->exists ? $category->is_active : true))>
        <label class="form-check-label" for="is_active">Activo</label>
    </div>
</div>

<div class="col-12 d-flex justify-content-end gap-2">
    <a class="btn btn-outline-secondary" href="{{ route('admin.categories.index') }}">Cancelar</a>
    <button class="btn btn-primary" type="submit">Guardar categoría</button>
</div>
