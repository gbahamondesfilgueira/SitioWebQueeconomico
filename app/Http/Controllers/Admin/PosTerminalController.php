<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PosTerminal;
use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class PosTerminalController extends Controller
{
    public function index()
    {
        return view('admin.pos.terminals.index', [
            'terminals' => PosTerminal::query()->with(['warehouse', 'location'])->latest()->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.pos.terminals.create', $this->formData());
    }

    public function store(Request $request)
    {
        $terminal = PosTerminal::query()->create($this->validateData($request));
        AuditLogger::record('created', 'pos', "Terminal POS creado {$terminal->code}");
        return redirect()->route('admin.pos.terminals.index')->with('success', 'Terminal POS creado.');
    }

    public function edit(PosTerminal $terminal)
    {
        return view('admin.pos.terminals.edit', ['terminal' => $terminal] + $this->formData());
    }

    public function update(Request $request, PosTerminal $terminal)
    {
        $terminal->update($this->validateData($request, $terminal->id));
        AuditLogger::record('updated', 'pos', "Terminal POS editado {$terminal->code}");
        return redirect()->route('admin.pos.terminals.index')->with('success', 'Terminal POS actualizado.');
    }

    public function toggle(PosTerminal $terminal)
    {
        $terminal->update(['is_active' => ! $terminal->is_active]);
        AuditLogger::record('toggled', 'pos', "Terminal POS {$terminal->code} cambió estado");
        return back()->with('success', 'Estado del terminal actualizado.');
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'code' => ['required', 'string', 'max:60', 'unique:pos_terminals,code'.($id ? ','.$id : '')],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'location_id' => ['nullable', 'exists:warehouse_locations,id'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }

    private function formData(): array
    {
        return [
            'warehouses' => Warehouse::query()->where('is_active', true)->orderBy('name')->get(),
            'locations' => WarehouseLocation::query()->where('is_active', true)->orderBy('name')->get(),
        ];
    }
}
