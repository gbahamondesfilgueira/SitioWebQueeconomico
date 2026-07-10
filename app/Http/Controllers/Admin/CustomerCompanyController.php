<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerCompanyRequest;
use App\Models\CustomerCompany;
use App\Models\CustomerProfile;
use App\Services\CustomerCrmService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerCompanyController extends Controller
{
    public function create(CustomerProfile $customer): View
    {
        return view('admin.customers.companies.form', ['customer' => $customer, 'company' => new CustomerCompany()]);
    }

    public function store(CustomerCompanyRequest $request, CustomerProfile $customer, CustomerCrmService $service): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        $service->saveCompany($customer, $data);

        return redirect()->route('admin.customers.show', $customer)->with('success', 'Empresa guardada.');
    }

    public function edit(CustomerProfile $customer, CustomerCompany $company): View
    {
        abort_unless($company->customer_profile_id === $customer->id, 404);

        return view('admin.customers.companies.form', compact('customer', 'company'));
    }

    public function update(CustomerCompanyRequest $request, CustomerProfile $customer, CustomerCompany $company, CustomerCrmService $service): RedirectResponse
    {
        abort_unless($company->customer_profile_id === $customer->id, 404);
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $service->saveCompany($customer, $data, $company);

        return redirect()->route('admin.customers.show', $customer)->with('success', 'Empresa actualizada.');
    }
}
