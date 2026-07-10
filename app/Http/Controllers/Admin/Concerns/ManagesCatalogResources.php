<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

trait ManagesCatalogResources
{
    public function index(Request $request): View
    {
        $query = $this->modelClass::query();

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($subQuery) use ($search): void {
                foreach ($this->searchColumns as $column) {
                    $subQuery->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        return view($this->viewPrefix.'.index', [
            'items' => $this->applyIndexQuery($query)->paginate(12)->withQueryString(),
            'search' => $search,
            'config' => $this->catalogConfig(),
        ]);
    }

    public function create(): View
    {
        return view($this->viewPrefix.'.create', [
            'item' => new $this->modelClass(),
            'config' => $this->catalogConfig(),
            ...$this->formData(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $item = $this->modelClass::query()->create($this->prepareData($request, $data));

        AuditLogger::record('created', $this->auditModule, "{$this->singularLabel} creado: {$item->name}");

        return redirect()->route($this->routePrefix.'.index')->with('success', "{$this->singularLabel} creado correctamente.");
    }

    public function edit($item): View
    {
        $item = $this->resolveItem($item);

        return view($this->viewPrefix.'.edit', [
            'item' => $item,
            'config' => $this->catalogConfig(),
            ...$this->formData($item),
        ]);
    }

    public function update(Request $request, $item): RedirectResponse
    {
        $item = $this->resolveItem($item);
        $data = $this->validatedData($request, $item);
        $item->update($this->prepareData($request, $data, $item));

        AuditLogger::record('updated', $this->auditModule, "{$this->singularLabel} editado: {$item->name}");

        return redirect()->route($this->routePrefix.'.index')->with('success', "{$this->singularLabel} actualizado correctamente.");
    }

    public function destroy($item): RedirectResponse
    {
        $item = $this->resolveItem($item);
        $item->delete();

        AuditLogger::record('deleted', $this->auditModule, "{$this->singularLabel} eliminado: {$item->name}");

        return back()->with('success', "{$this->singularLabel} eliminado correctamente.");
    }

    public function toggleActive($item): RedirectResponse
    {
        $item = $this->resolveItem($item);
        $item->forceFill(['is_active' => ! $item->is_active])->save();

        AuditLogger::record(
            $item->is_active ? 'activated' : 'deactivated',
            $this->auditModule,
            ($item->is_active ? "{$this->singularLabel} activado: " : "{$this->singularLabel} desactivado: ").$item->name,
        );

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    protected function validatedData(Request $request, $item = null): array
    {
        return $request->validate($this->rules($item));
    }

    protected function resolveItem($item)
    {
        $class = $this->modelClass;

        return $item instanceof $class
            ? $item
            : $class::query()->findOrFail($item);
    }

    protected function rules($item = null): array
    {
        return collect($this->fields)
            ->mapWithKeys(fn ($field) => [$field['name'] => $this->rulesForField($field, $item)])
            ->all();
    }

    protected function rulesForField(array $field, $item = null): array
    {
        $rules = $field['rules'] ?? [];

        if (($field['unique'] ?? false) === true) {
            $rules[] = Rule::unique($this->tableName, $field['name'])->ignore($item);
        }

        return $rules;
    }

    protected function prepareData(Request $request, array $data, $item = null): array
    {
        if ($this->hasActiveToggle) {
            $data['is_active'] = $request->boolean('is_active');
        }

        return $data;
    }

    protected function formData($item = null): array
    {
        return [];
    }

    protected function applyIndexQuery($query)
    {
        return $query->latest();
    }

    protected function catalogConfig(): array
    {
        return [
            'title' => $this->title,
            'singular' => $this->singularLabel,
            'routePrefix' => $this->routePrefix,
            'fields' => $this->fields,
            'columns' => $this->columns,
            'hasActiveToggle' => $this->hasActiveToggle,
            'allowDelete' => $this->allowDelete,
            'typeOptions' => $this->typeOptions ?? [],
        ];
    }
}
