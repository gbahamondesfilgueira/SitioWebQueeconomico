@php
    $primaryImage = old(
        'selected_primary_image_path',
        optional($product->images->firstWhere('is_primary', true))->image_path
    );

    $selectedGallery = collect(
        old('gallery_image_paths', $product->images->pluck('image_path')->all())
    );

    $mediaUrl = function (?string $path): ?string {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
    };
@endphp

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Datos generales</div>
            <div class="card-body row g-3">
                <div class="col-md-8">
                    <label class="form-label" for="name">Nombre</label>
                    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="product_type">Tipo</label>
                    <select class="form-select @error('product_type') is-invalid @enderror" id="product_type" name="product_type">
                        <option value="simple" @selected(old('product_type', $product->product_type ?: 'simple') === 'simple')>Simple</option>
                        <option value="variable" @selected(old('product_type', $product->product_type) === 'variable')>Variable</option>
                    </select>
                    @error('product_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="slug">Slug</label>
                    <input class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $product->slug) }}" placeholder="Se genera automaticamente">
                    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="sku">SKU</label>
                    <input class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" placeholder="Automatico: QE-0003">
                    <div class="form-text">Si lo dejas vacio se genera automaticamente.</div>
                    @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="barcode">Codigo de barras</label>
                    <input class="form-control @error('barcode') is-invalid @enderror" id="barcode" name="barcode" value="{{ old('barcode', $product->barcode) }}">
                    @error('barcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Clasificacion</div>
            <div class="card-body row g-3">
                @foreach ([['category_id', 'Categoria', $categories], ['brand_id', 'Marca', $brands], ['supplier_id', 'Proveedor', $suppliers], ['origin_country_id', 'Pais origen', $countries], ['tax_id', 'Impuesto', $taxes]] as [$field, $label, $items])
                    <div class="col-md-6">
                        <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                        <select class="form-select" id="{{ $field }}" name="{{ $field }}">
                            <option value="">Sin asignar</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}" @selected(old($field, $product->{$field}) == $item->id)>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
                <div class="col-12">
                    <label class="form-label" for="tag_ids">Etiquetas</label>
                    <select class="form-select" id="tag_ids" name="tag_ids[]" multiple size="5">
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @selected(collect(old('tag_ids', $product->tags->pluck('id')->all()))->contains($tag->id))>{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Precios y oferta</div>
            <div class="card-body row g-3">
                @foreach ([['cost_price','Costo'], ['regular_price','Precio normal'], ['sale_price','Precio oferta']] as [$field, $label])
                    <div class="col-md-4">
                        <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                        <input class="form-control @error($field) is-invalid @enderror" id="{{ $field }}" name="{{ $field }}" type="number" min="0" step="0.01" value="{{ old($field, $product->{$field}) }}">
                        @error($field) <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                @endforeach
                <div class="col-md-6">
                    <label class="form-label" for="sale_starts_at">Inicio oferta</label>
                    <input class="form-control @error('sale_starts_at') is-invalid @enderror" id="sale_starts_at" name="sale_starts_at" type="datetime-local" value="{{ old('sale_starts_at', optional($product->sale_starts_at)->format('Y-m-d\TH:i')) }}">
                    @error('sale_starts_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="sale_ends_at">Termino oferta</label>
                    <input class="form-control @error('sale_ends_at') is-invalid @enderror" id="sale_ends_at" name="sale_ends_at" type="datetime-local" value="{{ old('sale_ends_at', optional($product->sale_ends_at)->format('Y-m-d\TH:i')) }}">
                    @error('sale_ends_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Peso y medidas</div>
            <div class="card-body row g-3">
                @foreach ([['weight','Peso'], ['height','Alto'], ['width','Ancho'], ['length','Largo']] as [$field, $label])
                    <div class="col-md-3">
                        <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                        <input class="form-control @error($field) is-invalid @enderror" id="{{ $field }}" name="{{ $field }}" type="number" min="0" step="0.001" value="{{ old($field, $product->{$field}) }}">
                        @error($field) <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                @endforeach
                <div class="col-md-6">
                    <label class="form-label" for="weight_unit_id">Unidad de peso</label>
                    <select class="form-select" id="weight_unit_id" name="weight_unit_id">
                        <option value="">Sin unidad</option>
                        @foreach ($weightUnits as $unit)
                            <option value="{{ $unit->id }}" @selected(old('weight_unit_id', $product->weight_unit_id) == $unit->id)>{{ $unit->name }} ({{ $unit->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="dimension_unit_id">Unidad de dimension</label>
                    <select class="form-select" id="dimension_unit_id" name="dimension_unit_id">
                        <option value="">Sin unidad</option>
                        @foreach ($dimensionUnits as $unit)
                            <option value="{{ $unit->id }}" @selected(old('dimension_unit_id', $product->dimension_unit_id) == $unit->id)>{{ $unit->name }} ({{ $unit->code }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Descripciones</div>
            <div class="card-body row g-3">
                @foreach ([['short_description','Descripcion corta',3], ['long_description','Descripcion larga',5], ['technical_description','Descripcion tecnica',5]] as [$field, $label, $rows])
                    <div class="col-12">
                        <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                        <textarea class="form-control" id="{{ $field }}" name="{{ $field }}" rows="{{ $rows }}">{{ old($field, $product->{$field}) }}</textarea>
                        @if ($field === 'technical_description')
                            <div class="form-text">Puedes pegar HTML basico. Las tablas se mostraran como ficha tecnica en la tienda.</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">SEO</div>
            <div class="card-body row g-3">
                <div class="col-12"><label class="form-label" for="seo_title">Titulo SEO</label><input class="form-control" id="seo_title" name="seo_title" value="{{ old('seo_title', $product->seo_title) }}"></div>
                <div class="col-12"><label class="form-label" for="seo_description">Descripcion SEO</label><textarea class="form-control" id="seo_description" name="seo_description" rows="3">{{ old('seo_description', $product->seo_description) }}</textarea></div>
                <div class="col-12"><label class="form-label" for="seo_keywords">Keywords SEO</label><textarea class="form-control" id="seo_keywords" name="seo_keywords" rows="2">{{ old('seo_keywords', $product->seo_keywords) }}</textarea></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Publicacion</div>
            <div class="card-body">
                @foreach ([['is_active','Activo'], ['is_featured','Destacado'], ['is_visible','Visible']] as [$field, $label])
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input @error($field) is-invalid @enderror" type="checkbox" id="{{ $field }}" name="{{ $field }}" value="1" @checked(old($field, $product->exists ? $product->{$field} : true))>
                        <label class="form-check-label" for="{{ $field }}">{{ $label }}</label>
                        @error($field) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        @if($field === 'is_visible')
                            <div class="form-text">Visible significa que aparece en la tienda publica. Si queda desmarcado, el producto sigue existiendo en el administrador.</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Imagenes</div>
            <div class="card-body">
                <input type="hidden" name="selected_primary_image_path" data-primary-media-input value="{{ $primaryImage }}">
                <input type="hidden" name="gallery_selection_submitted" value="1">

                <div class="d-grid gap-2 mb-3">
                    <button class="btn btn-outline-dark" type="button" data-bs-toggle="modal" data-bs-target="#primaryMediaModal">Seleccionar imagen principal</button>
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#galleryMediaModal">Administrar galeria</button>
                </div>

                <div class="border rounded bg-light p-2 text-center mb-3" data-primary-media-preview>
                    @if($primaryImage)
                    <img src="{{ $mediaUrl($primaryImage) }}" class="img-fluid rounded" alt="Imagen principal">
                    @else
                        <span class="text-secondary small">Sin imagen principal seleccionada</span>
                    @endif
                </div>

                <label class="form-label" for="images">Subir imagenes nuevas</label>
                <input class="form-control @error('images.*') is-invalid @enderror" id="images" name="images[]" type="file" accept=".jpg,.jpeg,.png,.webp" multiple>
                @error('images.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                <div class="form-text">Las imagenes nuevas se agregaran a la galeria al guardar.</div>

                <label class="form-label mt-3" for="image_alt_text">Texto alternativo</label>
                <input class="form-control" id="image_alt_text" name="image_alt_text" value="{{ old('image_alt_text') }}">

                @if ($product->exists && $product->images->isNotEmpty())
                    <div class="small fw-semibold text-secondary mt-3 mb-2">Imagenes cargadas actualmente</div>
                    <div class="row g-2">
                        @foreach ($product->images as $image)
                            <div class="col-6">
                                <div class="border rounded p-2 h-100">
                                    <img src="{{ $mediaUrl($image->image_path) }}" class="img-fluid rounded mb-2" alt="{{ $image->alt_text }}">
                                    @if($image->is_primary)
                                        <span class="badge text-bg-success">Principal</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-text mt-2">Para quitar imagenes de la galeria, desmarcalas en Administrar galeria y guarda.</div>
                @endif
            </div>
        </div>

        <div class="modal fade" id="primaryMediaModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Seleccionar imagen principal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Subir nueva imagen</label>
                        <input
                            type="file"
                            name="primary_image"
                            class="form-control @error('primary_image') is-invalid @enderror"
                            accept=".jpg,.jpeg,.png,.webp"
                            data-primary-upload-input
                        >
                        @error('primary_image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        <div class="form-text mb-3" data-primary-upload-status>Selecciona un archivo y presiona "Usar como imagen principal".</div>

                        <div class="row g-3">
                            @forelse($mediaImages as $path)
                                <div class="col-6 col-md-3">
                                    <button class="btn p-1 border w-100 qe-media-pick" type="button" data-media-primary="{{ $path }}" data-media-url="{{ $mediaUrl($path) }}" data-bs-dismiss="modal">
                                        <img src="{{ $mediaUrl($path) }}" class="img-fluid rounded" alt="">
                                    </button>
                                </div>
                            @empty
                                <div class="col-12 text-secondary">Todavia no hay imagenes en la biblioteca. Sube una imagen nueva y guarda el producto.</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" data-apply-primary-upload disabled>Usar como imagen principal</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="galleryMediaModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Administrar galeria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Subir nuevas imagenes</label>
                        <input type="file" name="images[]" class="form-control mb-3" multiple accept=".jpg,.jpeg,.png,.webp">
                        <div class="form-text mb-3">Si subes imagenes nuevas, se agregaran al producto al guardar.</div>

                        <p class="text-secondary small">Marca las imagenes que quieres asociar a este producto. El orden visible se mantiene por orden de seleccion y guardado.</p>
                        <div class="row g-3">
                            @forelse($mediaImages as $path)
                                <div class="col-6 col-md-3">
                                    <label class="qe-media-checkbox border rounded p-2 w-100 h-100">
                                        <input type="checkbox" name="gallery_image_paths[]" value="{{ $path }}" class="form-check-input me-1" @checked($selectedGallery->contains($path))>
                                        <img src="{{ $mediaUrl($path) }}" class="img-fluid rounded mt-2" alt="">
                                    </label>
                                </div>
                            @empty
                                <div class="col-12 text-secondary">Todavia no hay imagenes en la biblioteca. Sube una imagen nueva y guarda el producto.</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Aplicar seleccion</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Productos relacionados</div>
            <div class="card-body">
                <label class="form-label" for="relation_type">Tipo relacion</label>
                <select class="form-select mb-3" id="relation_type" name="relation_type">
                    @foreach (['related' => 'Relacionado', 'cross_sell' => 'Venta cruzada', 'up_sell' => 'Venta superior'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('relation_type', optional($product->relatedProducts->first())->pivot->relation_type ?? 'related') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <label class="form-label" for="related_product_ids">Productos</label>
                <select class="form-select" id="related_product_ids" name="related_product_ids[]" multiple size="8">
                    @foreach ($relatedProducts as $relatedProduct)
                        <option value="{{ $relatedProduct->id }}" @selected(collect(old('related_product_ids', $product->relatedProducts->pluck('id')->all()))->contains($relatedProduct->id))>{{ $relatedProduct->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="d-grid gap-2 mt-3">
            <button class="btn btn-primary" type="submit">Guardar producto</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.products.index') }}">Cancelar</a>
        </div>
    </div>
</div>
