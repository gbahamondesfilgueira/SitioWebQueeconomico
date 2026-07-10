<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExternalProductMapping;
use App\Models\Integration;
use App\Models\Product;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class ExternalProductMappingController extends Controller
{
    public function index() { return view('admin.integrations.product_mappings.index', ['mappings' => ExternalProductMapping::with(['integration', 'product', 'variant'])->paginate(30)]); }
    public function create() { return view('admin.integrations.product_mappings.create', ['integrations' => Integration::orderBy('name')->get(), 'products' => Product::orderBy('name')->get()]); }
    public function store(Request $request)
    {
        $data = $request->validate(['integration_id' => 'required|exists:integrations,id', 'product_id' => 'required|exists:products,id', 'product_variant_id' => 'nullable|exists:product_variants,id', 'external_product_id' => 'required', 'external_variant_id' => 'nullable', 'external_sku' => 'nullable', 'sync_stock' => 'nullable|boolean', 'sync_price' => 'nullable|boolean']);
        ExternalProductMapping::query()->create($data + ['sync_stock' => $request->boolean('sync_stock', true), 'sync_price' => $request->boolean('sync_price', true)]);
        AuditLogger::record('created', 'external_product_mappings', 'Mapeo producto creado');
        return redirect()->route('admin.external-product-mappings.index')->with('success', 'Mapeo creado.');
    }
    public function edit(ExternalProductMapping $externalProductMapping) { return view('admin.integrations.product_mappings.edit', ['mapping' => $externalProductMapping, 'integrations' => Integration::orderBy('name')->get(), 'products' => Product::orderBy('name')->get()]); }
    public function update(Request $request, ExternalProductMapping $externalProductMapping)
    {
        $data = $request->validate(['external_product_id' => 'required', 'external_variant_id' => 'nullable', 'external_sku' => 'nullable', 'sync_stock' => 'nullable|boolean', 'sync_price' => 'nullable|boolean']);
        $externalProductMapping->update($data + ['sync_stock' => $request->boolean('sync_stock'), 'sync_price' => $request->boolean('sync_price')]);
        AuditLogger::record('updated', 'external_product_mappings', 'Mapeo producto actualizado');
        return redirect()->route('admin.external-product-mappings.index')->with('success', 'Mapeo actualizado.');
    }
}
