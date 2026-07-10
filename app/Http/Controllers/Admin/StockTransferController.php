<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockTransfer;
use App\Models\Warehouse;
use App\Services\AuditLogger;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockTransferController extends Controller
{
    public function index(): View
    {
        return view('admin.stock_transfers.index', ['transfers' => StockTransfer::with(['originWarehouse', 'destinationWarehouse', 'items'])->latest()->paginate(20)]);
    }
    public function create(): View { return view('admin.stock_transfers.create', $this->formData()); }
    public function store(Request $request): RedirectResponse
    {
        abort_if(! $request->user()->hasRole(['super-admin', 'administrador']), 403);
        $data = $request->validate([
            'origin_warehouse_id' => ['required', 'different:destination_warehouse_id', 'exists:warehouses,id'],
            'origin_location_id' => ['nullable', 'exists:warehouse_locations,id'],
            'destination_warehouse_id' => ['required', 'exists:warehouses,id'],
            'destination_location_id' => ['nullable', 'exists:warehouse_locations,id'],
            'notes' => ['nullable', 'string'], 'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'], 'items.*.product_variant_id' => ['nullable', 'exists:product_variants,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'], 'items.*.notes' => ['nullable', 'string'],
        ]);
        $transfer = DB::transaction(function () use ($data, $request) {
            $transfer = StockTransfer::create([...collect($data)->except('items')->all(), 'status' => 'pending', 'created_by' => $request->user()->id]);
            foreach ($data['items'] as $item) $transfer->items()->create($item);
            return $transfer;
        });
        AuditLogger::record('created', 'stock_transfers', "Transferencia creada #{$transfer->id}");
        return redirect()->route('admin.stock-transfers.show', $transfer)->with('success', 'Transferencia creada.');
    }
    public function show(StockTransfer $stock_transfer): View { return view('admin.stock_transfers.show', ['transfer' => $stock_transfer->load(['originWarehouse', 'destinationWarehouse', 'originLocation', 'destinationLocation', 'items.product', 'items.variant'])]); }
    public function send(StockTransfer $stock_transfer, InventoryService $inventory): RedirectResponse
    {
        abort_if(! request()->user()->hasRole(['super-admin', 'administrador']), 403);
        $inventory->sendTransfer($stock_transfer->load('items'));
        return back()->with('success', 'Transferencia enviada.');
    }
    public function receive(StockTransfer $stock_transfer, InventoryService $inventory): RedirectResponse
    {
        abort_if(! request()->user()->hasRole(['super-admin', 'administrador']), 403);
        $inventory->receiveTransfer($stock_transfer->load('items'));
        return back()->with('success', 'Transferencia recibida.');
    }
    private function formData(): array
    {
        return ['warehouses' => Warehouse::where('is_active', true)->with('locations')->get(), 'products' => Product::where('is_active', true)->orderBy('name')->get(), 'variants' => ProductVariant::where('is_active', true)->with('product')->get()];
    }
}
