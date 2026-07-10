<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WarehouseLocationController extends Controller
{
    public function index(Warehouse $warehouse): View { return view('admin.warehouses.locations.index', ['warehouse' => $warehouse->load('locations')]); }
    public function create(Warehouse $warehouse): View { return view('admin.warehouses.locations.create', ['warehouse' => $warehouse, 'location' => new WarehouseLocation()]); }
    public function store(Request $request, Warehouse $warehouse): RedirectResponse
    {
        $data = $this->validated($request, $warehouse);
        $data['is_active'] = $request->boolean('is_active');
        $location = $warehouse->locations()->create($data);
        AuditLogger::record('created', 'warehouses', "Ubicación creada: {$warehouse->code}/{$location->code}");
        return redirect()->route('admin.warehouses.locations.index', $warehouse)->with('success', 'Ubicación creada.');
    }
    public function edit(Warehouse $warehouse, WarehouseLocation $location): View
    {
        abort_if($location->warehouse_id !== $warehouse->id, 404);
        return view('admin.warehouses.locations.edit', compact('warehouse', 'location'));
    }
    public function update(Request $request, Warehouse $warehouse, WarehouseLocation $location): RedirectResponse
    {
        abort_if($location->warehouse_id !== $warehouse->id, 404);
        $data = $this->validated($request, $warehouse, $location);
        $data['is_active'] = $request->boolean('is_active');
        $location->update($data);
        AuditLogger::record('updated', 'warehouses', "Ubicación editada: {$warehouse->code}/{$location->code}");
        return redirect()->route('admin.warehouses.locations.index', $warehouse)->with('success', 'Ubicación actualizada.');
    }
    private function validated(Request $request, Warehouse $warehouse, ?WarehouseLocation $location = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('warehouse_locations', 'code')->where('warehouse_id', $warehouse->id)->ignore($location)],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
