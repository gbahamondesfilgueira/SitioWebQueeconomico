<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerAddressRequest;
use App\Models\CustomerAddress;
use App\Models\CustomerProfile;
use App\Services\CustomerCrmService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerAddressController extends Controller
{
    public function create(CustomerProfile $customer): View
    {
        return view('admin.customers.addresses.form', ['customer' => $customer, 'address' => new CustomerAddress()]);
    }

    public function store(CustomerAddressRequest $request, CustomerProfile $customer, CustomerCrmService $service): RedirectResponse
    {
        $data = $request->validated();
        $data['is_default'] = $request->boolean('is_default');
        $data['is_active'] = $request->boolean('is_active', true);
        $service->saveAddress($customer, $data);

        return redirect()->route('admin.customers.show', $customer)->with('success', 'Dirección guardada.');
    }

    public function edit(CustomerProfile $customer, CustomerAddress $address): View
    {
        abort_unless($address->customer_profile_id === $customer->id, 404);

        return view('admin.customers.addresses.form', compact('customer', 'address'));
    }

    public function update(CustomerAddressRequest $request, CustomerProfile $customer, CustomerAddress $address, CustomerCrmService $service): RedirectResponse
    {
        abort_unless($address->customer_profile_id === $customer->id, 404);
        $data = $request->validated();
        $data['is_default'] = $request->boolean('is_default');
        $data['is_active'] = $request->boolean('is_active');
        $service->saveAddress($customer, $data, $address);

        return redirect()->route('admin.customers.show', $customer)->with('success', 'Dirección actualizada.');
    }
}
