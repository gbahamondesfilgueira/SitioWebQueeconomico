<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\MeasurementUnit;
use App\Models\OriginCountry;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Supplier;
use App\Models\Tag;
use App\Models\Tax;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $products = Product::query()
            ->with(['category', 'brand', 'images'])
            ->withCount('variants')
            ->when($search, fn ($query) => $query->where(fn ($sub) => $sub
                ->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%")))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.products.index', compact('products', 'search'));
    }

    public function create(): View
    {
        return view('admin.products.create', $this->formData(new Product));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data = $this->prepareData($request, $data);

        if ($data['product_type'] === 'variable' && $data['is_visible']) {
            $data['is_visible'] = false;
            session()->flash('error', 'El producto variable fue creado como no visible hasta que tenga al menos una variante.');
        }

        $product = DB::transaction(function () use ($request, $data) {
            $product = Product::query()->create($data);
            $product->tags()->sync($request->input('tag_ids', []));
            $this->syncRelatedProducts($request, $product);
            $this->storeImages($request, $product);
            $this->syncMediaLibrarySelection($request, $product);

            return $product;
        });

        AuditLogger::record('created', 'products', "Producto creado: {$product->name}");

        return redirect()->route('admin.products.edit', $product)->with('success', 'Producto creado correctamente.');
    }

    public function show(Product $product): View
    {
        $product->load(['category', 'brand', 'supplier', 'tax', 'originCountry', 'tags', 'images', 'variants.attributeValues.attribute', 'relatedProducts']);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', $this->formData($product));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validatedData($request, $product);
        $data = $this->prepareData($request, $data);

        if ($data['product_type'] === 'variable' && $data['is_visible'] && ! $product->variants()->where('is_active', true)->exists()) {
            return back()->withErrors(['is_visible' => 'Un producto variable necesita al menos una variante activa para quedar visible.'])->withInput();
        }

        DB::transaction(function () use ($request, $product, $data): void {
            $product->update($data);
            $product->tags()->sync($request->input('tag_ids', []));
            $this->syncRelatedProducts($request, $product);
            $this->storeImages($request, $product);
            $this->syncMediaLibrarySelection($request, $product);

            if ($request->filled('primary_image_id')) {
                $this->setPrimaryImage($product, (int) $request->input('primary_image_id'));
            }
        });

        AuditLogger::record('updated', 'products', "Producto editado: {$product->name}");

        return redirect()->route('admin.products.edit', $product)->with('success', 'Producto actualizado correctamente.');
    }

    public function toggleActive(Product $product): RedirectResponse
    {
        if ($product->is_active && $product->variants()->where('is_active', true)->exists()) {
            session()->flash('error', 'Advertencia: el producto tiene variantes activas. Se desactivó solo el producto, no sus variantes.');
        }

        $product->forceFill(['is_active' => ! $product->is_active])->save();

        AuditLogger::record($product->is_active ? 'activated' : 'deactivated', 'products', ($product->is_active ? 'Producto activado: ' : 'Producto desactivado: ').$product->name);

        return back()->with('success', 'Estado del producto actualizado.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $productName = $product->name;
        $product->delete();

        AuditLogger::record('deleted', 'products', "Producto enviado a papelera: {$productName}");

        return redirect()->route('admin.products.index')->with('success', 'Producto enviado a papelera.');
    }

    public function deleteImage(Product $product, ProductImage $image): RedirectResponse
    {
        abort_if($image->product_id !== $product->id, 404);

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        AuditLogger::record('deleted_image', 'product_images', "Imagen eliminada del producto: {$product->name}");

        return back()->with('success', 'Imagen eliminada correctamente.');
    }

    public function makePrimaryImage(Product $product, ProductImage $image): RedirectResponse
    {
        abort_if($image->product_id !== $product->id, 404);
        $this->setPrimaryImage($product, $image->id);

        return back()->with('success', 'Imagen principal actualizada.');
    }

    private function validatedData(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'tax_id' => ['nullable', 'exists:taxes,id'],
            'origin_country_id' => ['nullable', 'exists:origin_countries,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($product)],
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($product), Rule::unique('product_variants', 'sku')],
            'barcode' => ['nullable', 'string', 'max:100', Rule::unique('products', 'barcode')->ignore($product), Rule::unique('product_variants', 'barcode')],
            'product_type' => ['required', Rule::in(['simple', 'variable'])],
            'short_description' => ['nullable', 'string'],
            'long_description' => ['nullable', 'string'],
            'technical_description' => ['nullable', 'string'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'regular_price' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lte:regular_price'],
            'sale_starts_at' => ['nullable', 'date'],
            'sale_ends_at' => ['nullable', 'date', 'after_or_equal:sale_starts_at'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'height' => ['nullable', 'numeric', 'min:0'],
            'width' => ['nullable', 'numeric', 'min:0'],
            'length' => ['nullable', 'numeric', 'min:0'],
            'weight_unit_id' => ['nullable', 'exists:measurement_units,id'],
            'dimension_unit_id' => ['nullable', 'exists:measurement_units,id'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_visible' => ['nullable', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'seo_keywords' => ['nullable', 'string'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
            'related_product_ids' => ['nullable', 'array'],
            'related_product_ids.*' => ['exists:products,id'],
            'relation_type' => ['required', Rule::in(['related', 'cross_sell', 'up_sell'])],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:'.implode(',', config('products.allowed_image_mimes')), 'max:'.config('products.max_image_size_kb')],
            'primary_image' => ['nullable', 'image', 'mimes:'.implode(',', config('products.allowed_image_mimes')), 'max:'.config('products.max_image_size_kb')],
            'image_alt_text' => ['nullable', 'string', 'max:255'],
            'selected_primary_image_path' => ['nullable', 'string', 'max:255'],
            'gallery_image_paths' => ['nullable', 'array'],
            'gallery_image_paths.*' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function prepareData(Request $request, array $data): array
    {
        foreach (['is_active', 'is_featured', 'is_visible'] as $field) {
            $data[$field] = $request->boolean($field);
        }

        if (blank($data['sku'] ?? null)) {
            $data['sku'] = $this->generateNextSku();
        }

        return $data;
    }

    private function generateNextSku(): string
    {
        $lastSku = Product::withTrashed()
            ->where('sku', 'like', 'QE-%')
            ->orderByDesc('id')
            ->value('sku');

        $nextNumber = 1;

        if ($lastSku && preg_match('/QE-(\d+)/', $lastSku, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        } else {
            $nextNumber = (Product::withTrashed()->count() ?? 0) + 1;
        }

        do {
            $sku = 'QE-'.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (
            Product::withTrashed()->where('sku', $sku)->exists()
            || DB::table('product_variants')->where('sku', $sku)->exists()
        );

        return $sku;
    }

    private function formData(Product $product): array
    {
        return [
            'product' => $product->load(['tags', 'images', 'relatedProducts']),
            'categories' => Category::query()->orderBy('name')->get(),
            'brands' => Brand::query()->orderBy('name')->get(),
            'suppliers' => Supplier::query()->orderBy('name')->get(),
            'taxes' => Tax::query()->where('is_active', true)->orderBy('name')->get(),
            'countries' => OriginCountry::query()->orderBy('name')->get(),
            'weightUnits' => MeasurementUnit::query()->whereIn('type', ['weight', 'unit'])->orderBy('name')->get(),
            'dimensionUnits' => MeasurementUnit::query()->where('type', 'dimension')->orderBy('name')->get(),
            'tags' => Tag::query()->orderBy('name')->get(),
            'relatedProducts' => Product::query()->when($product->exists, fn ($query) => $query->whereKeyNot($product->id))->orderBy('name')->get(),
            'mediaImages' => collect(Storage::disk('public')->allFiles('products'))
                ->filter(fn ($path) => preg_match('/\.(jpe?g|png|webp)$/i', $path))
                ->sort()
                ->values(),
        ];
    }

    private function syncRelatedProducts(Request $request, Product $product): void
    {
        DB::table('related_products')->where('product_id', $product->id)->delete();

        foreach ($request->input('related_product_ids', []) as $relatedId) {
            if ((int) $relatedId === $product->id) {
                continue;
            }

            DB::table('related_products')->updateOrInsert([
                'product_id' => $product->id,
                'related_product_id' => $relatedId,
                'relation_type' => $request->input('relation_type', 'related'),
            ], [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function storeImages(Request $request, Product $product): void
    {
        if (! $request->hasFile('images') && ! $request->hasFile('primary_image')) {
            return;
        }

        foreach ($request->file('images', []) as $index => $image) {
            $created = $product->images()->create([
                'image_path' => $image->store('products', 'public'),
                'alt_text' => $request->input('image_alt_text'),
                'sort_order' => $product->images()->count() + $index + 1,
                'is_primary' => ! $product->images()->exists(),
            ]);

            if ($created->is_primary) {
                $this->setPrimaryImage($product, $created->id);
            }
        }

        if ($request->hasFile('primary_image')) {
            $primary = $product->images()->create([
                'image_path' => $request->file('primary_image')->store('products', 'public'),
                'alt_text' => $request->input('image_alt_text') ?: $product->name,
                'sort_order' => 0,
                'is_primary' => false,
            ]);

            $this->setPrimaryImage($product, $primary->id);
        }

        AuditLogger::record('uploaded_image', 'product_images', "Imágenes cargadas para producto: {$product->name}");
    }

    private function setPrimaryImage(Product $product, int $imageId): void
    {
        $product->images()->update(['is_primary' => false]);
        $product->images()->whereKey($imageId)->update(['is_primary' => true]);

        AuditLogger::record('changed_primary_image', 'product_images', "Imagen principal cambiada: {$product->name}");
    }

    private function syncMediaLibrarySelection(Request $request, Product $product): void
    {
        $galleryPaths = collect($request->input('gallery_image_paths', []))
            ->filter(fn ($path) => is_string($path) && $this->isPublicProductImage($path))
            ->unique()
            ->values();

        foreach ($galleryPaths as $index => $path) {
            $image = $product->images()->firstOrCreate([
                'image_path' => $path,
            ], [
                'alt_text' => $request->input('image_alt_text') ?: $product->name,
                'sort_order' => $index + 1,
                'is_primary' => false,
            ]);

            $image->forceFill(['sort_order' => $index + 1])->save();
        }

        $selectedPrimary = $request->hasFile('primary_image')
            ? null
            : $request->input('selected_primary_image_path');
        $keepPaths = $galleryPaths->values();

        if ($request->filled('gallery_selection_submitted') && ! $request->hasFile('images') && ! $request->hasFile('primary_image')) {
            if (is_string($selectedPrimary) && $this->isPublicProductImage($selectedPrimary)) {
                $keepPaths->push($selectedPrimary);
            }

            $product->images()
                ->whereNotIn('image_path', $keepPaths->unique()->values()->all())
                ->delete();
        }

        if (is_string($selectedPrimary) && $this->isPublicProductImage($selectedPrimary)) {
            $image = $product->images()->firstOrCreate([
                'image_path' => $selectedPrimary,
            ], [
                'alt_text' => $request->input('image_alt_text') ?: $product->name,
                'sort_order' => 0,
                'is_primary' => false,
            ]);

            $this->setPrimaryImage($product, $image->id);
        } elseif (! $product->images()->where('is_primary', true)->exists() && $product->images()->exists()) {
            $this->setPrimaryImage($product, (int) $product->images()->orderBy('sort_order')->value('id'));
        }
    }

    private function isPublicProductImage(string $path): bool
    {
        return str_starts_with($path, 'products/')
            && (bool) preg_match('/\.(jpe?g|png|webp)$/i', $path)
            && Storage::disk('public')->exists($path);
    }
}
