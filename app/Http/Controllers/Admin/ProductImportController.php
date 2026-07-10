<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImport;
use App\Services\ProductBulkImportService;
use Illuminate\Http\Request;

class ProductImportController extends Controller
{
    public function index()
    {
        return view('admin.imports.products.index', [
            'imports' => ProductImport::query()->with('creator')->latest()->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.imports.products.create');
    }

    public function store(Request $request, ProductBulkImportService $importer)
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:20480'],
        ]);

        $path = $validated['file']->store('imports/products');

        $import = ProductImport::query()->create([
            'import_type' => 'woocommerce_csv',
            'file_path' => $path,
            'status' => 'pending',
            'created_by' => $request->user()->id,
        ]);

        $importer->importWooCommerceCsv($import);

        return redirect()->route('admin.imports.products.show', $import)->with('success', 'Importación procesada.');
    }

    public function show(ProductImport $import)
    {
        return view('admin.imports.products.show', compact('import'));
    }
}
