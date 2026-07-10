<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AttributeController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $attributes = Attribute::query()
            ->withCount('values')
            ->when($search, fn ($query) => $query->where(fn ($sub) => $sub
                ->where('name', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%")))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.attributes.index', compact('attributes', 'search'));
    }

    public function create(): View
    {
        return view('admin.attributes.create', ['attribute' => new Attribute()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedAttribute($request);
        $data['is_active'] = $request->boolean('is_active');

        $attribute = Attribute::query()->create($data);

        AuditLogger::record('created', 'attributes', "Atributo creado: {$attribute->name}");

        return redirect()->route('admin.attributes.show', $attribute)->with('success', 'Atributo creado correctamente.');
    }

    public function show(Attribute $attribute): View
    {
        return view('admin.attributes.show', [
            'attribute' => $attribute->load('values'),
            'editingValue' => null,
        ]);
    }

    public function edit(Attribute $attribute): View
    {
        return view('admin.attributes.edit', compact('attribute'));
    }

    public function update(Request $request, Attribute $attribute): RedirectResponse
    {
        $data = $this->validatedAttribute($request, $attribute);
        $data['is_active'] = $request->boolean('is_active');

        $attribute->update($data);

        AuditLogger::record('updated', 'attributes', "Atributo editado: {$attribute->name}");

        return redirect()->route('admin.attributes.show', $attribute)->with('success', 'Atributo actualizado correctamente.');
    }

    public function toggleActive(Attribute $attribute): RedirectResponse
    {
        $attribute->forceFill(['is_active' => ! $attribute->is_active])->save();

        AuditLogger::record($attribute->is_active ? 'activated' : 'deactivated', 'attributes', ($attribute->is_active ? 'Atributo activado: ' : 'Atributo desactivado: ').$attribute->name);

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    public function storeValue(Request $request, Attribute $attribute): RedirectResponse
    {
        $data = $this->validatedValue($request, $attribute);
        $data['is_active'] = $request->boolean('is_active');

        $value = $attribute->values()->create($data);

        AuditLogger::record('created_value', 'attributes', "Valor creado: {$attribute->name} - {$value->value}");

        return back()->with('success', 'Valor creado correctamente.');
    }

    public function editValue(Attribute $attribute, AttributeValue $value): View
    {
        abort_if($value->attribute_id !== $attribute->id, 404);

        return view('admin.attributes.show', [
            'attribute' => $attribute->load('values'),
            'editingValue' => $value,
        ]);
    }

    public function updateValue(Request $request, Attribute $attribute, AttributeValue $value): RedirectResponse
    {
        abort_if($value->attribute_id !== $attribute->id, 404);

        $data = $this->validatedValue($request, $attribute, $value);
        $data['is_active'] = $request->boolean('is_active');
        $value->update($data);

        AuditLogger::record('updated_value', 'attributes', "Valor editado: {$attribute->name} - {$value->value}");

        return redirect()->route('admin.attributes.show', $attribute)->with('success', 'Valor actualizado correctamente.');
    }

    public function toggleValue(Attribute $attribute, AttributeValue $value): RedirectResponse
    {
        abort_if($value->attribute_id !== $attribute->id, 404);
        $value->forceFill(['is_active' => ! $value->is_active])->save();

        AuditLogger::record($value->is_active ? 'activated_value' : 'deactivated_value', 'attributes', "Valor actualizado: {$attribute->name} - {$value->value}");

        return back()->with('success', 'Estado del valor actualizado.');
    }

    private function validatedAttribute(Request $request, ?Attribute $attribute = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('attributes', 'slug')->ignore($attribute)],
            'type' => ['required', Rule::in(Attribute::TYPES)],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);
    }

    private function validatedValue(Request $request, Attribute $attribute, ?AttributeValue $value = null): array
    {
        return $request->validate([
            'value' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('attribute_values', 'slug')->where('attribute_id', $attribute->id)->ignore($value)],
            'color_hex' => ['nullable', 'string', 'max:20'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
