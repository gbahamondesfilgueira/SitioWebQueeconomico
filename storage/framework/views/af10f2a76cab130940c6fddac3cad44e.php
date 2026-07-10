<?php
    $primaryImage = old('selected_primary_image_path', optional($product->images->firstWhere('is_primary', true))->image_path);
    $selectedGallery = collect(old('gallery_image_paths', $product->images->pluck('image_path')->all()));
?>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Datos generales</div>
            <div class="card-body row g-3">
                <div class="col-md-8">
                    <label class="form-label" for="name">Nombre</label>
                    <input class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" value="<?php echo e(old('name', $product->name)); ?>" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="product_type">Tipo</label>
                    <select class="form-select <?php $__errorArgs = ['product_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="product_type" name="product_type">
                        <option value="simple" <?php if(old('product_type', $product->product_type ?: 'simple') === 'simple'): echo 'selected'; endif; ?>>Simple</option>
                        <option value="variable" <?php if(old('product_type', $product->product_type) === 'variable'): echo 'selected'; endif; ?>>Variable</option>
                    </select>
                    <?php $__errorArgs = ['product_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="slug">Slug</label>
                    <input class="form-control <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="slug" name="slug" value="<?php echo e(old('slug', $product->slug)); ?>" placeholder="Se genera automaticamente">
                    <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="sku">SKU</label>
                    <input class="form-control <?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="sku" name="sku" value="<?php echo e(old('sku', $product->sku)); ?>" placeholder="Automatico: QE-0003">
                    <div class="form-text">Si lo dejas vacio se genera automaticamente.</div>
                    <?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="barcode">Codigo de barras</label>
                    <input class="form-control <?php $__errorArgs = ['barcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="barcode" name="barcode" value="<?php echo e(old('barcode', $product->barcode)); ?>">
                    <?php $__errorArgs = ['barcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Clasificacion</div>
            <div class="card-body row g-3">
                <?php $__currentLoopData = [['category_id', 'Categoria', $categories], ['brand_id', 'Marca', $brands], ['supplier_id', 'Proveedor', $suppliers], ['origin_country_id', 'Pais origen', $countries], ['tax_id', 'Impuesto', $taxes]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$field, $label, $items]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6">
                        <label class="form-label" for="<?php echo e($field); ?>"><?php echo e($label); ?></label>
                        <select class="form-select" id="<?php echo e($field); ?>" name="<?php echo e($field); ?>">
                            <option value="">Sin asignar</option>
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($item->id); ?>" <?php if(old($field, $product->{$field}) == $item->id): echo 'selected'; endif; ?>><?php echo e($item->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12">
                    <label class="form-label" for="tag_ids">Etiquetas</label>
                    <select class="form-select" id="tag_ids" name="tag_ids[]" multiple size="5">
                        <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tag->id); ?>" <?php if(collect(old('tag_ids', $product->tags->pluck('id')->all()))->contains($tag->id)): echo 'selected'; endif; ?>><?php echo e($tag->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Precios y oferta</div>
            <div class="card-body row g-3">
                <?php $__currentLoopData = [['cost_price','Costo'], ['regular_price','Precio normal'], ['sale_price','Precio oferta']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$field, $label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-4">
                        <label class="form-label" for="<?php echo e($field); ?>"><?php echo e($label); ?></label>
                        <input class="form-control <?php $__errorArgs = [$field];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="<?php echo e($field); ?>" name="<?php echo e($field); ?>" type="number" min="0" step="0.01" value="<?php echo e(old($field, $product->{$field})); ?>">
                        <?php $__errorArgs = [$field];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6">
                    <label class="form-label" for="sale_starts_at">Inicio oferta</label>
                    <input class="form-control <?php $__errorArgs = ['sale_starts_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="sale_starts_at" name="sale_starts_at" type="datetime-local" value="<?php echo e(old('sale_starts_at', optional($product->sale_starts_at)->format('Y-m-d\TH:i'))); ?>">
                    <?php $__errorArgs = ['sale_starts_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="sale_ends_at">Termino oferta</label>
                    <input class="form-control <?php $__errorArgs = ['sale_ends_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="sale_ends_at" name="sale_ends_at" type="datetime-local" value="<?php echo e(old('sale_ends_at', optional($product->sale_ends_at)->format('Y-m-d\TH:i'))); ?>">
                    <?php $__errorArgs = ['sale_ends_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Peso y medidas</div>
            <div class="card-body row g-3">
                <?php $__currentLoopData = [['weight','Peso'], ['height','Alto'], ['width','Ancho'], ['length','Largo']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$field, $label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-3">
                        <label class="form-label" for="<?php echo e($field); ?>"><?php echo e($label); ?></label>
                        <input class="form-control <?php $__errorArgs = [$field];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="<?php echo e($field); ?>" name="<?php echo e($field); ?>" type="number" min="0" step="0.001" value="<?php echo e(old($field, $product->{$field})); ?>">
                        <?php $__errorArgs = [$field];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6">
                    <label class="form-label" for="weight_unit_id">Unidad de peso</label>
                    <select class="form-select" id="weight_unit_id" name="weight_unit_id">
                        <option value="">Sin unidad</option>
                        <?php $__currentLoopData = $weightUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($unit->id); ?>" <?php if(old('weight_unit_id', $product->weight_unit_id) == $unit->id): echo 'selected'; endif; ?>><?php echo e($unit->name); ?> (<?php echo e($unit->code); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="dimension_unit_id">Unidad de dimension</label>
                    <select class="form-select" id="dimension_unit_id" name="dimension_unit_id">
                        <option value="">Sin unidad</option>
                        <?php $__currentLoopData = $dimensionUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($unit->id); ?>" <?php if(old('dimension_unit_id', $product->dimension_unit_id) == $unit->id): echo 'selected'; endif; ?>><?php echo e($unit->name); ?> (<?php echo e($unit->code); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Descripciones</div>
            <div class="card-body row g-3">
                <?php $__currentLoopData = [['short_description','Descripcion corta',3], ['long_description','Descripcion larga',5], ['technical_description','Descripcion tecnica',5]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$field, $label, $rows]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-12">
                        <label class="form-label" for="<?php echo e($field); ?>"><?php echo e($label); ?></label>
                        <textarea class="form-control" id="<?php echo e($field); ?>" name="<?php echo e($field); ?>" rows="<?php echo e($rows); ?>"><?php echo e(old($field, $product->{$field})); ?></textarea>
                        <?php if($field === 'technical_description'): ?>
                            <div class="form-text">Puedes pegar HTML basico. Las tablas se mostraran como ficha tecnica en la tienda.</div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">SEO</div>
            <div class="card-body row g-3">
                <div class="col-12"><label class="form-label" for="seo_title">Titulo SEO</label><input class="form-control" id="seo_title" name="seo_title" value="<?php echo e(old('seo_title', $product->seo_title)); ?>"></div>
                <div class="col-12"><label class="form-label" for="seo_description">Descripcion SEO</label><textarea class="form-control" id="seo_description" name="seo_description" rows="3"><?php echo e(old('seo_description', $product->seo_description)); ?></textarea></div>
                <div class="col-12"><label class="form-label" for="seo_keywords">Keywords SEO</label><textarea class="form-control" id="seo_keywords" name="seo_keywords" rows="2"><?php echo e(old('seo_keywords', $product->seo_keywords)); ?></textarea></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Publicacion</div>
            <div class="card-body">
                <?php $__currentLoopData = [['is_active','Activo'], ['is_featured','Destacado'], ['is_visible','Visible']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$field, $label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input <?php $__errorArgs = [$field];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="checkbox" id="<?php echo e($field); ?>" name="<?php echo e($field); ?>" value="1" <?php if(old($field, $product->exists ? $product->{$field} : true)): echo 'checked'; endif; ?>>
                        <label class="form-check-label" for="<?php echo e($field); ?>"><?php echo e($label); ?></label>
                        <?php $__errorArgs = [$field];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php if($field === 'is_visible'): ?>
                            <div class="form-text">Visible significa que aparece en la tienda publica. Si queda desmarcado, el producto sigue existiendo en el administrador.</div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Imagenes</div>
            <div class="card-body">
                <input type="hidden" name="selected_primary_image_path" data-primary-media-input value="<?php echo e($primaryImage); ?>">
                <input type="hidden" name="gallery_selection_submitted" value="1">

                <div class="d-grid gap-2 mb-3">
                    <button class="btn btn-outline-dark" type="button" data-bs-toggle="modal" data-bs-target="#primaryMediaModal">Seleccionar imagen principal</button>
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#galleryMediaModal">Administrar galeria</button>
                </div>

                <div class="border rounded bg-light p-2 text-center mb-3" data-primary-media-preview>
                    <?php if($primaryImage): ?>
                        <img src="<?php echo e(asset('storage/'.$primaryImage)); ?>" class="img-fluid rounded" alt="Imagen principal">
                    <?php else: ?>
                        <span class="text-secondary small">Sin imagen principal seleccionada</span>
                    <?php endif; ?>
                </div>

                <label class="form-label" for="images">Subir imagenes nuevas</label>
                <input class="form-control <?php $__errorArgs = ['images.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="images" name="images[]" type="file" accept=".jpg,.jpeg,.png,.webp" multiple>
                <?php $__errorArgs = ['images.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <div class="form-text">Las imagenes nuevas se agregaran a la galeria al guardar.</div>

                <label class="form-label mt-3" for="image_alt_text">Texto alternativo</label>
                <input class="form-control" id="image_alt_text" name="image_alt_text" value="<?php echo e(old('image_alt_text')); ?>">

                <?php if($product->exists && $product->images->isNotEmpty()): ?>
                    <div class="small fw-semibold text-secondary mt-3 mb-2">Imagenes cargadas actualmente</div>
                    <div class="row g-2">
                        <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-6">
                                <div class="border rounded p-2 h-100">
                                    <img src="<?php echo e(asset('storage/'.$image->image_path)); ?>" class="img-fluid rounded mb-2" alt="<?php echo e($image->alt_text); ?>">
                                    <?php if($image->is_primary): ?>
                                        <span class="badge text-bg-success">Principal</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="form-text mt-2">Para quitar imagenes de la galeria, desmarcalas en Administrar galeria y guarda.</div>
                <?php endif; ?>
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
                        <input type="file" name="images[]" class="form-control mb-3" accept=".jpg,.jpeg,.png,.webp">
                        <div class="form-text mb-3">Si subes una imagen nueva, guarda el producto y luego podras seleccionarla desde la biblioteca.</div>

                        <div class="row g-3">
                            <?php $__empty_1 = true; $__currentLoopData = $mediaImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $path): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="col-6 col-md-3">
                                    <button class="btn p-1 border w-100 qe-media-pick" type="button" data-media-primary="<?php echo e($path); ?>" data-media-url="<?php echo e(asset('storage/'.$path)); ?>" data-bs-dismiss="modal">
                                        <img src="<?php echo e(asset('storage/'.$path)); ?>" class="img-fluid rounded" alt="">
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="col-12 text-secondary">Todavia no hay imagenes en la biblioteca. Sube una imagen nueva y guarda el producto.</div>
                            <?php endif; ?>
                        </div>
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
                            <?php $__empty_1 = true; $__currentLoopData = $mediaImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $path): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="col-6 col-md-3">
                                    <label class="qe-media-checkbox border rounded p-2 w-100 h-100">
                                        <input type="checkbox" name="gallery_image_paths[]" value="<?php echo e($path); ?>" class="form-check-input me-1" <?php if($selectedGallery->contains($path)): echo 'checked'; endif; ?>>
                                        <img src="<?php echo e(asset('storage/'.$path)); ?>" class="img-fluid rounded mt-2" alt="">
                                    </label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="col-12 text-secondary">Todavia no hay imagenes en la biblioteca. Sube una imagen nueva y guarda el producto.</div>
                            <?php endif; ?>
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
                    <?php $__currentLoopData = ['related' => 'Relacionado', 'cross_sell' => 'Venta cruzada', 'up_sell' => 'Venta superior']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>" <?php if(old('relation_type', optional($product->relatedProducts->first())->pivot->relation_type ?? 'related') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <label class="form-label" for="related_product_ids">Productos</label>
                <select class="form-select" id="related_product_ids" name="related_product_ids[]" multiple size="8">
                    <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($relatedProduct->id); ?>" <?php if(collect(old('related_product_ids', $product->relatedProducts->pluck('id')->all()))->contains($relatedProduct->id)): echo 'selected'; endif; ?>><?php echo e($relatedProduct->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="d-grid gap-2 mt-3">
            <button class="btn btn-primary" type="submit">Guardar producto</button>
            <a class="btn btn-outline-secondary" href="<?php echo e(route('admin.products.index')); ?>">Cancelar</a>
        </div>
    </div>
</div>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\products\partials\form.blade.php ENDPATH**/ ?>