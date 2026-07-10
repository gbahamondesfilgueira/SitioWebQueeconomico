<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
    public function index() { return view('admin.branches.index', ['branches' => Branch::query()->with('company')->latest()->paginate(20)]); }
    public function create() { return view('admin.branches.create', ['branch' => new Branch(), 'companies' => Company::query()->orderBy('name')->get()]); }
    public function edit(Branch $branch) { return view('admin.branches.edit', ['branch' => $branch, 'companies' => Company::query()->orderBy('name')->get()]); }

    public function store(Request $request)
    {
        $branch = Branch::query()->create($this->validated($request));
        AuditLogger::record('created', 'branches', "Sucursal {$branch->name} creada.");
        return redirect()->route('admin.branches.index')->with('success', 'Sucursal creada.');
    }

    public function update(Request $request, Branch $branch)
    {
        $branch->update($this->validated($request, $branch));
        AuditLogger::record('updated', 'branches', "Sucursal {$branch->name} editada.");
        return redirect()->route('admin.branches.index')->with('success', 'Sucursal actualizada.');
    }

    private function validated(Request $request, ?Branch $branch = null): array
    {
        return $request->validate([
            'company_id' => ['nullable', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('branches', 'code')->ignore($branch)],
            'address' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:100'],
            'commune' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
