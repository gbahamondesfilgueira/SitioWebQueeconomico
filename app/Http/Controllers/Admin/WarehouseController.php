<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Services\AuditLogger;
use App\Services\DeliveryRegionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    public function __construct(private DeliveryRegionService $deliveryRegions) {}

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $warehouses = Warehouse::query()->withCount('locations')
            ->when($search, fn ($q) => $q->where(fn ($s) => $s->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%")))
            ->latest()->paginate(12)->withQueryString();

        return view('admin.warehouses.index', compact('warehouses', 'search'));
    }

    public function create(): View
    {
        return view('admin.warehouses.create', ['warehouse' => new Warehouse]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['region'] = $this->deliveryRegions->normalize($data['region']);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_central'] = $request->boolean('is_central');
        $warehouse = DB::transaction(function () use ($data) {
            if ($data['is_central']) {
                Warehouse::query()->update(['is_central' => false]);
            }

            return Warehouse::query()->create($data);
        });
        AuditLogger::record('created', 'warehouses', "Bodega creada: {$warehouse->code}");

        return redirect()->route('admin.warehouses.show', $warehouse)->with('success', 'Bodega creada correctamente.');
    }

    public function show(Warehouse $warehouse): View
    {
        return view('admin.warehouses.show', ['warehouse' => $warehouse->load('locations')]);
    }

    public function edit(Warehouse $warehouse): View
    {
        return view('admin.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse): RedirectResponse
    {
        $data = $this->validated($request, $warehouse);
        $data['region'] = $this->deliveryRegions->normalize($data['region']);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_central'] = $request->boolean('is_central');
        DB::transaction(function () use ($warehouse, $data) {
            if ($data['is_central']) {
                Warehouse::query()->where('id', '!=', $warehouse->id)->update(['is_central' => false]);
            }
            $warehouse->update($data);
        });
        AuditLogger::record('updated', 'warehouses', "Bodega editada: {$warehouse->code}");

        return redirect()->route('admin.warehouses.show', $warehouse)->with('success', 'Bodega actualizada correctamente.');
    }

    public function toggleActive(Warehouse $warehouse): RedirectResponse
    {
        $warehouse->forceFill(['is_active' => ! $warehouse->is_active])->save();
        AuditLogger::record($warehouse->is_active ? 'activated' : 'deactivated', 'warehouses', "Bodega {$warehouse->code}");

        return back()->with('success', 'Estado actualizado.');
    }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        abort_if(! request()->user()->hasRole(['super-admin', 'administrador']), 403);
        $warehouse->delete();

        return redirect()->route('admin.warehouses.index')->with('success', 'Bodega enviada a papelera.');
    }

    private function validated(Request $request, ?Warehouse $warehouse = null): array
    {
        $request->merge(['region' => $this->deliveryRegions->normalize($request->input('region'))]);

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('warehouses', 'code')->ignore($warehouse)],
            'type' => ['required', Rule::in(['main', 'branch', 'pos', 'ecommerce'])],
            'address' => ['nullable', 'string', 'max:255'], 'city' => ['nullable', 'string', 'max:100'],
            'region' => ['required', Rule::in(array_keys($this->deliveryRegions->regions()))], 'commune' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'], 'email' => ['nullable', 'email', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'is_central' => ['nullable', 'boolean'], 'fulfillment_priority' => ['required', 'integer', 'min:1', 'max:9999'],
        ]);
    }
}
