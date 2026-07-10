<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductVariantController extends Controller
{
    public function index(Product $product): View
    {
        return view('admin.products.variants.index', [
            'product' => $product,
            'variants' => $product->variants()->with('attributeValues.attribute')->latest()->paginate(12),
        ]);
    }

    public function create(Product $product): View
    {
        return view('admin.products.variants.create', $this->formData($product, new ProductVariant()));
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_if($product->product_type !== 'variable', 422, 'Solo los productos variables pueden tener variantes.');

        $data = $this->validatedData($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['product_id'] = $product->id;

        $attributeValueIds = array_values(array_filter($request->input('attribute_value_ids', [])));
        $this->ensureUniqueCombination($product, $attributeValueIds);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('product-variants', 'public');
        }

        $variant = DB::transaction(function () use ($data, $attributeValueIds) {
            $variant = ProductVariant::query()->create($data);
            $this->syncAttributeValues($variant, $attributeValueIds);
            return $variant;
        });

        AuditLogger::record('created', 'product_variants', "Variante creada: {$product->name} / {$variant->sku}");

        return redirect()->route('admin.products.variants.index', $product)->with('success', 'Variante creada correctamente.');
    }

    public function edit(Product $product, ProductVariant $variant): View
    {
        abort_if($variant->product_id !== $product->id, 404);

        return view('admin.products.variants.edit', $this->formData($product, $variant));
    }

    public function update(Request $request, Product $product, ProductVariant $variant): RedirectResponse
    {
        abort_if($variant->product_id !== $product->id, 404);

        $data = $this->validatedData($request, $variant);
        $data['is_active'] = $request->boolean('is_active');

        $attributeValueIds = array_values(array_filter($request->input('attribute_value_ids', [])));
        $this->ensureUniqueCombination($product, $attributeValueIds, $variant);

        if ($request->hasFile('image')) {
            if ($variant->image_path) {
                Storage::disk('public')->delete($variant->image_path);
            }
            $data['image_path'] = $request->file('image')->store('product-variants', 'public');
        }

        DB::transaction(function () use ($variant, $data, $attributeValueIds): void {
            $variant->update($data);
            $this->syncAttributeValues($variant, $attributeValueIds);
        });

        AuditLogger::record('updated', 'product_variants', "Variante editada: {$product->name} / {$variant->sku}");

        return redirect()->route('admin.products.variants.index', $product)->with('success', 'Variante actualizada correctamente.');
    }

    public function toggleActive(Product $product, ProductVariant $variant): RedirectResponse
    {
        abort_if($variant->product_id !== $product->id, 404);
        $variant->forceFill(['is_active' => ! $variant->is_active])->save();

        AuditLogger::record($variant->is_active ? 'activated' : 'deactivated', 'product_variants', ($variant->is_active ? 'Variante activada: ' : 'Variante desactivada: ').$variant->sku);

        return back()->with('success', 'Estado de variante actualizado.');
    }

    public function destroy(Product $product, ProductVariant $variant): RedirectResponse
    {
        abort_if($variant->product_id !== $product->id, 404);
        $variantName = $variant->sku ?: $variant->name;
        $variant->delete();

        AuditLogger::record('deleted', 'product_variants', "Variante enviada a papelera: {$variantName}");

        return back()->with('success', 'Variante enviada a papelera.');
    }

    private function validatedData(Request $request, ?ProductVariant $variant = null): array
    {
        return $request->validate([
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('product_variants', 'sku')->ignore($variant), Rule::unique('products', 'sku')],
            'barcode' => ['nullable', 'string', 'max:100', Rule::unique('product_variants', 'barcode')->ignore($variant), Rule::unique('products', 'barcode')],
            'name' => ['nullable', 'string', 'max:255'],
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
            'image' => ['nullable', 'image', 'mimes:'.implode(',', config('products.allowed_image_mimes')), 'max:'.config('products.max_image_size_kb')],
            'attribute_value_ids' => ['required', 'array', 'min:1'],
            'attribute_value_ids.*' => ['exists:attribute_values,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function formData(Product $product, ProductVariant $variant): array
    {
        return [
            'product' => $product,
            'variant' => $variant->load('attributeValues'),
            'attributes' => Attribute::query()->with(['values' => fn ($query) => $query->where('is_active', true)])->where('is_active', true)->orderBy('sort_order')->get(),
            'weightUnits' => MeasurementUnit::query()->whereIn('type', ['weight', 'unit'])->orderBy('name')->get(),
            'dimensionUnits' => MeasurementUnit::query()->where('type', 'dimension')->orderBy('name')->get(),
        ];
    }

    private function syncAttributeValues(ProductVariant $variant, array $attributeValueIds): void
    {
        DB::table('product_variant_attribute_values')->where('product_variant_id', $variant->id)->delete();

        foreach ($attributeValueIds as $valueId) {
            $value = \App\Models\AttributeValue::query()->findOrFail($valueId);
            DB::table('product_variant_attribute_values')->insert([
                'product_variant_id' => $variant->id,
                'attribute_id' => $value->attribute_id,
                'attribute_value_id' => $value->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function ensureUniqueCombination(Product $product, array $attributeValueIds, ?ProductVariant $ignoreVariant = null): void
    {
        sort($attributeValueIds);
        $signature = implode('-', $attributeValueIds);

        foreach ($product->variants()->with('attributeValues')->get() as $variant) {
            if ($ignoreVariant && $variant->id === $ignoreVariant->id) {
                continue;
            }

            $existing = $variant->attributeValues->pluck('id')->sort()->implode('-');
            if ($existing === $signature) {
                abort(422, 'Ya existe una variante con la misma combinación de atributos.');
            }
        }
    }
}
