<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $categories = Category::query()
            ->with('parent')
            ->withCount('children')
            ->when($search, fn ($query) => $query->where(fn ($subQuery) => $subQuery
                ->where('name', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.categories.index', compact('categories', 'search'));
    }

    public function create(): View
    {
        return view('admin.categories.create', [
            'category' => new Category(),
            'parents' => $this->parentOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['image_path'] = $request->file('image')?->store('categories', 'public');

        $category = Category::query()->create($data);

        AuditLogger::record('created', 'categories', "Categoría creada: {$category->name}");

        return redirect()->route('admin.categories.index')->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', [
            'category' => $category,
            'parents' => $this->parentOptions($category),
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $this->validatedData($request, $category);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }

            $data['image_path'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        AuditLogger::record('updated', 'categories', "Categoría editada: {$category->name}");

        return redirect()->route('admin.categories.index')->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->children()->exists()) {
            return back()->with('error', 'No se puede eliminar una categoría con categorías hijas.');
        }

        if ($category->image_path) {
            Storage::disk('public')->delete($category->image_path);
        }

        $categoryName = $category->name;
        $category->delete();

        AuditLogger::record('deleted', 'categories', "Categoría eliminada: {$categoryName}");

        return back()->with('success', 'Categoría eliminada correctamente.');
    }

    public function toggleActive(Category $category): RedirectResponse
    {
        $category->forceFill(['is_active' => ! $category->is_active])->save();

        AuditLogger::record(
            $category->is_active ? 'activated' : 'deactivated',
            'categories',
            ($category->is_active ? 'Categoría activada: ' : 'Categoría desactivada: ').$category->name,
        );

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    private function validatedData(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'parent_id' => ['nullable', 'exists:categories,id', Rule::notIn([$category?->id])],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($category)],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);
    }

    private function parentOptions(?Category $category = null)
    {
        return Category::query()
            ->when($category, fn ($query) => $query->whereKeyNot($category->id))
            ->orderBy('name')
            ->get();
    }
}
