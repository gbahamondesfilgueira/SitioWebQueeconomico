<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $brands = Brand::query()
            ->when($search, fn ($query) => $query->where(fn ($subQuery) => $subQuery
                ->where('name', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%")
                ->orWhere('website', 'like', "%{$search}%")))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.brands.index', compact('brands', 'search'));
    }

    public function create(): View
    {
        return view('admin.brands.create', ['brand' => new Brand()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['logo_path'] = $request->file('logo')?->store('brands', 'public');

        $brand = Brand::query()->create($data);

        AuditLogger::record('created', 'brands', "Marca creada: {$brand->name}");

        return redirect()->route('admin.brands.index')->with('success', 'Marca creada correctamente.');
    }

    public function edit(Brand $brand): View
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $data = $this->validatedData($request, $brand);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('logo')) {
            if ($brand->logo_path) {
                Storage::disk('public')->delete($brand->logo_path);
            }

            $data['logo_path'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($data);

        AuditLogger::record('updated', 'brands', "Marca editada: {$brand->name}");

        return redirect()->route('admin.brands.index')->with('success', 'Marca actualizada correctamente.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        if ($brand->logo_path) {
            Storage::disk('public')->delete($brand->logo_path);
        }

        $brandName = $brand->name;
        $brand->delete();

        AuditLogger::record('deleted', 'brands', "Marca eliminada: {$brandName}");

        return back()->with('success', 'Marca eliminada correctamente.');
    }

    public function toggleActive(Brand $brand): RedirectResponse
    {
        $brand->forceFill(['is_active' => ! $brand->is_active])->save();

        AuditLogger::record(
            $brand->is_active ? 'activated' : 'deactivated',
            'brands',
            ($brand->is_active ? 'Marca activada: ' : 'Marca desactivada: ').$brand->name,
        );

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    private function validatedData(Request $request, ?Brand $brand = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('brands', 'slug')->ignore($brand)],
            'description' => ['nullable', 'string'],
            'website' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
