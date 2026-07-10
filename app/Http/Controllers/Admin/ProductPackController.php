<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPack;
use App\Models\ProductVariant;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductPackController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $packs = ProductPack::query()->withCount('items')
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%"))
            ->latest()->paginate(12)->withQueryString();
        return view('admin.product_packs.index', compact('packs', 'search'));
    }

    public function create(): View { return view('admin.product_packs.create', $this->formData(new ProductPack())); }
    public function show(ProductPack $product_pack): View { return view('admin.product_packs.show', ['pack' => $product_pack->load('items.product', 'items.variant')]); }
    public function edit(ProductPack $product_pack): View { return view('admin.product_packs.edit', $this->formData($product_pack)); }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $pack = DB::transaction(fn () => $this->persist(new ProductPack(), $request, $data));
        AuditLogger::record('created', 'product_packs', "Pack creado: {$pack->name}");
        return redirect()->route('admin.product-packs.show', $pack)->with('success', 'Pack creado correctamente.');
    }

    public function update(Request $request, ProductPack $product_pack): RedirectResponse
    {
        $data = $this->validated($request, $product_pack);
        DB::transaction(fn () => $this->persist($product_pack, $request, $data));
        AuditLogger::record('updated', 'product_packs', "Pack editado: {$product_pack->name}");
        return redirect()->route('admin.product-packs.show', $product_pack)->with('success', 'Pack actualizado correctamente.');
    }

    public function toggleActive(ProductPack $product_pack): RedirectResponse
    {
        $product_pack->forceFill(['is_active' => ! $product_pack->is_active])->save();
        AuditLogger::record($product_pack->is_active ? 'activated' : 'deactivated', 'product_packs', "Pack: {$product_pack->name}");
        return back()->with('success', 'Estado actualizado.');
    }

    private function persist(ProductPack $pack, Request $request, array $data): ProductPack
    {
        $items = collect($data['items'])->filter(fn ($item) => ! empty($item['product_id']) && ! empty($item['quantity']))->values();
        if ($items->isEmpty()) {
            throw \Illuminate\Validation\ValidationException::withMessages(['items' => 'El pack debe tener al menos un producto.']);
        }
        unset($data['items']);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_visible'] = $request->boolean('is_visible');
        if ($request->hasFile('image')) {
            if ($pack->image_path) Storage::disk('public')->delete($pack->image_path);
            $data['image_path'] = $request->file('image')->store('product-packs', 'public');
        }
        $pack->fill($data)->save();
        $pack->items()->delete();
        foreach ($items as $item) {
            $pack->items()->create($item);
        }
        return $pack;
    }

    private function validated(Request $request, ?ProductPack $pack = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('product_packs', 'slug')->ignore($pack)],
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('product_packs', 'sku')->ignore($pack)],
            'barcode' => ['nullable', 'string', 'max:100', Rule::unique('product_packs', 'barcode')->ignore($pack)],
            'description' => ['nullable', 'string'],
            'regular_price' => ['nullable', 'numeric', 'min:0'],
            'pack_price' => ['required', 'numeric', 'min:0.01'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
            'is_visible' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['nullable', 'exists:products,id'],
            'items.*.product_variant_id' => ['nullable', 'exists:product_variants,id'],
            'items.*.quantity' => ['nullable', 'integer', 'min:1'],
        ]);
    }

    private function formData(ProductPack $pack): array
    {
        return ['pack' => $pack->load('items'), 'products' => Product::orderBy('name')->get(), 'variants' => ProductVariant::with('product')->get()];
    }
}
