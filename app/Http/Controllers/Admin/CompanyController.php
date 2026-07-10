<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index() { return view('admin.companies.index', ['companies' => Company::query()->latest()->paginate(20)]); }
    public function create() { return view('admin.companies.create', ['company' => new Company()]); }
    public function edit(Company $company) { return view('admin.companies.edit', compact('company')); }

    public function store(Request $request)
    {
        $company = Company::query()->create($this->validated($request));
        AuditLogger::record('created', 'companies', "Empresa {$company->name} creada.");
        return redirect()->route('admin.companies.index')->with('success', 'Empresa creada.');
    }

    public function update(Request $request, Company $company)
    {
        $company->update($this->validated($request));
        AuditLogger::record('updated', 'companies', "Empresa {$company->name} editada.");
        return redirect()->route('admin.companies.index')->with('success', 'Empresa actualizada.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'rut' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'currency' => ['required', 'string', 'max:10'],
            'tax_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
