<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockAdjustment;
use App\Models\Warehouse;
use App\Services\AuditLogger;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockAdjustmentController extends Controller
{
    public function index(): View
    {
        return view('admin.stock_adjustments.index', ['adjustments' => StockAdjustment::query()->with(['warehouse', 'location', 'product', 'variant', 'user'])->latest()->paginate(20)]);
    }
    public function create(Request $request): View
    {
        return view('admin.stock_adjustments.create', [
            ...$this->formData(),
            'selectedProductId' => $request->integer('product_id') ?: null,
            'selectedVariantId' => $request->integer('product_variant_id') ?: null,
        ]);
    }
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'warehouse_id' => ['required', 'exists:warehouses,id'], 'warehouse_location_id' => ['nullable', 'exists:warehouse_locations,id'],
            'product_id' => ['required', 'exists:products,id'], 'product_variant_id' => ['nullable', 'exists:product_variants,id'],
            'adjustment_type' => ['required', 'in:increase,decrease'], 'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'in:correction,damage,loss,inventory_count,return,other'], 'notes' => ['nullable', 'string'],
        ]);
        $adjustment = StockAdjustment::query()->create([...$data, 'user_id' => $request->user()->id, 'status' => 'pending']);
        AuditLogger::record('created', 'stock_adjustments', "Ajuste creado #{$adjustment->id}");
        return redirect()->route('admin.stock-adjustments.show', $adjustment)->with('success', 'Ajuste creado como pendiente.');
    }
    public function show(StockAdjustment $stock_adjustment): View { return view('admin.stock_adjustments.show', ['adjustment' => $stock_adjustment->load(['warehouse', 'location', 'product', 'variant', 'user', 'approver'])]); }
    public function approve(StockAdjustment $stock_adjustment, InventoryService $inventory): RedirectResponse
    {
        abort_if(! request()->user()->hasRole(['super-admin', 'administrador']), 403);
        $inventory->approveAdjustment($stock_adjustment);
        return back()->with('success', 'Ajuste aprobado y aplicado.');
    }
    public function reject(StockAdjustment $stock_adjustment): RedirectResponse
    {
        abort_if(! request()->user()->hasRole(['super-admin', 'administrador']), 403);
        if ($stock_adjustment->status !== 'pending') return back()->with('error', 'Solo se rechazan ajustes pendientes.');
        $stock_adjustment->update(['status' => 'rejected', 'approved_by' => request()->user()->id, 'approved_at' => now()]);
        AuditLogger::record('rejected', 'stock_adjustments', "Ajuste rechazado #{$stock_adjustment->id}");
        return back()->with('success', 'Ajuste rechazado.');
    }
    private function formData(): array
    {
        return ['warehouses' => Warehouse::where('is_active', true)->with('locations')->get(), 'products' => Product::where('is_active', true)->orderBy('name')->get(), 'variants' => ProductVariant::where('is_active', true)->with('product')->get()];
    }
}
