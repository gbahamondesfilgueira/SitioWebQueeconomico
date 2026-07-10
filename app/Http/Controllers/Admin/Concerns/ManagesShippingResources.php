<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

trait ManagesShippingResources
{
    public function index(Request $request): View
    {
        $class = $this->modelClass;
        return view('admin.shipping.shared.index', [
            'title' => $this->title,
            'route' => $this->route,
            'columns' => $this->columns,
            'items' => $class::query()->with($this->with ?? [])->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.shipping.shared.form', $this->formData(new $this->modelClass()));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());
        $data = $this->mutateData($request, $data);
        $class = $this->modelClass;
        $item = $class::query()->create($data);
        AuditLogger::record('created', $this->module, "{$this->title} creado");
        return redirect()->route("admin.shipping.{$this->route}.edit", $item)->with('success', 'Registro creado.');
    }

    public function edit($item): View
    {
        return view('admin.shipping.shared.form', $this->formData($this->resolve($item)));
    }

    public function update(Request $request, $item): RedirectResponse
    {
        $item = $this->resolve($item);
        $data = $request->validate($this->rules($item));
        $item->update($this->mutateData($request, $data));
        AuditLogger::record('updated', $this->module, "{$this->title} editado");
        return back()->with('success', 'Registro actualizado.');
    }

    public function toggle($item): RedirectResponse
    {
        $item = $this->resolve($item);
        $item->update(['is_active' => ! $item->is_active]);
        AuditLogger::record($item->is_active ? 'activated' : 'deactivated', $this->module, "{$this->title} estado actualizado");
        return back()->with('success', 'Estado actualizado.');
    }

    protected function formData($item): array
    {
        return [
            'title' => $this->title,
            'route' => $this->route,
            'fields' => $this->fields,
            'item' => $item,
            ...($this->extraFormData() ?? []),
        ];
    }

    protected function extraFormData(): array { return []; }
    protected function mutateData(Request $request, array $data): array { $data['is_active'] = $request->boolean('is_active', $data['is_active'] ?? true); return $data; }

    private function resolve($item)
    {
        $class = $this->modelClass;
        return $item instanceof $class ? $item : $class::query()->findOrFail($item);
    }
}
