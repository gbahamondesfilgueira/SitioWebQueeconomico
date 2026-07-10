<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerTag;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerTagController extends Controller
{
    public function index(): View
    {
        return view('admin.customer_tags.index', [
            'tags' => CustomerTag::query()->withCount('customerProfiles')->orderBy('name')->paginate(20),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:customer_tags,name'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $tag = CustomerTag::query()->create([
            ...$data,
            'slug' => Str::slug($data['name']),
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLogger::record('created', 'customer_tags', "Etiqueta cliente creada: {$tag->name}");

        return back()->with('success', 'Etiqueta creada.');
    }

    public function update(Request $request, CustomerTag $customerTag): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('customer_tags', 'name')->ignore($customerTag)],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $customerTag->update([
            ...$data,
            'slug' => Str::slug($data['name']),
            'is_active' => $request->boolean('is_active'),
        ]);

        AuditLogger::record('updated', 'customer_tags', "Etiqueta cliente editada: {$customerTag->name}");

        return back()->with('success', 'Etiqueta actualizada.');
    }
}
