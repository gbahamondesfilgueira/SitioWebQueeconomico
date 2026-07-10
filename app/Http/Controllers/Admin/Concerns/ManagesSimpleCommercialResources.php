<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

trait ManagesSimpleCommercialResources
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $items = $this->modelClass::query()
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%")->orWhere($this->codeColumn, 'like', "%{$search}%"))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.commercial.index', ['items' => $items, 'search' => $search, 'config' => $this->config()]);
    }

    public function create(): View
    {
        return view('admin.commercial.create', ['item' => new $this->modelClass(), 'config' => $this->config(), ...$this->formData()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $item = DB::transaction(fn () => $this->persist(new $this->modelClass(), $request, $data));
        AuditLogger::record('created', $this->auditModule, "{$this->singularLabel} creado: {$item->name}");
        return redirect()->route($this->routePrefix.'.show', $item)->with('success', "{$this->singularLabel} creado correctamente.");
    }

    public function show($item): View
    {
        $item = $this->resolveItem($item);
        return view('admin.commercial.show', ['item' => $item->load($this->showRelations), 'config' => $this->config()]);
    }

    public function edit($item): View
    {
        $item = $this->resolveItem($item);
        return view('admin.commercial.edit', ['item' => $item, 'config' => $this->config(), ...$this->formData($item)]);
    }

    public function update(Request $request, $item): RedirectResponse
    {
        $item = $this->resolveItem($item);
        $data = $this->validatedData($request, $item);
        DB::transaction(fn () => $this->persist($item, $request, $data));
        AuditLogger::record('updated', $this->auditModule, "{$this->singularLabel} editado: {$item->name}");
        return redirect()->route($this->routePrefix.'.show', $item)->with('success', "{$this->singularLabel} actualizado correctamente.");
    }

    public function toggleActive($item): RedirectResponse
    {
        $item = $this->resolveItem($item);
        $item->forceFill(['is_active' => ! $item->is_active])->save();
        AuditLogger::record($item->is_active ? 'activated' : 'deactivated', $this->auditModule, "{$this->singularLabel}: {$item->name}");
        return back()->with('success', 'Estado actualizado correctamente.');
    }

    protected function validatedData(Request $request, $item = null): array
    {
        $rules = [];
        foreach ($this->fields as $field) {
            $fieldRules = $field['rules'] ?? [];
            if (($field['unique'] ?? false) === true) {
                $fieldRules[] = Rule::unique($this->tableName, $field['name'])->ignore($item);
            }
            $rules[$field['name']] = $fieldRules;
        }
        return $request->validate($rules);
    }

    protected function persist($item, Request $request, array $data)
    {
        foreach ($this->booleanFields as $field) {
            $data[$field] = $request->boolean($field);
        }
        $item->fill($data)->save();
        $this->afterPersist($item, $request);
        return $item;
    }

    protected function afterPersist($item, Request $request): void {}
    protected function formData($item = null): array { return []; }
    protected function resolveItem($item) { $class = $this->modelClass; return $item instanceof $class ? $item : $class::query()->findOrFail($item); }
    protected function config(): array { return ['title' => $this->title, 'singular' => $this->singularLabel, 'routePrefix' => $this->routePrefix, 'fields' => $this->fields, 'columns' => $this->columns, 'booleanFields' => $this->booleanFields, 'options' => $this->options ?? []]; }
}
