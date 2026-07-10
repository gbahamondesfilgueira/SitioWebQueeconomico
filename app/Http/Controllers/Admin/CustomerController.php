<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerProfileRequest;
use App\Models\CouponUsage;
use App\Models\CustomerProfile;
use App\Models\CustomerTag;
use App\Models\PriceList;
use App\Models\Role;
use App\Services\AuditLogger;
use App\Services\CustomerCrmService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        return view('admin.customers.index', [
            'customers' => CustomerProfile::query()
                ->with(['user', 'tags'])
                ->when($search, fn ($query) => $query->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('rut', 'like', "%{$search}%");
                }))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'search' => $search,
            'stats' => $this->stats(),
        ]);
    }

    public function create(): View
    {
        return view('admin.customers.create', $this->formData(new CustomerProfile()));
    }

    public function store(CustomerProfileRequest $request, CustomerCrmService $service): RedirectResponse
    {
        $customerRole = Role::query()->where('slug', 'cliente')->first();
        $data = $request->validated();
        $data['role_id'] = $customerRole?->id;
        $data['is_active'] = $request->boolean('is_active', true);

        $profile = $service->createCustomer($data, $request->input('tag_ids', []), $request->input('password'));

        return redirect()->route('admin.customers.show', $profile)->with('success', 'Cliente creado correctamente.');
    }

    public function show(CustomerProfile $customer): View
    {
        $customer->load([
            'user',
            'preferredPriceList',
            'addresses',
            'companies.contacts',
            'documents',
            'favorites.product',
            'wishlistItems.product',
            'rewardTransactions',
            'notes.user',
            'tags',
        ]);

        return view('admin.customers.show', [
            'customer' => $customer,
            'couponUsages' => CouponUsage::query()->where('user_id', $customer->user_id)->latest('used_at')->limit(10)->get(),
        ]);
    }

    public function edit(CustomerProfile $customer): View
    {
        return view('admin.customers.edit', $this->formData($customer));
    }

    public function update(CustomerProfileRequest $request, CustomerProfile $customer, CustomerCrmService $service): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $service->updateCustomer($customer, $data, $request->input('tag_ids', []));

        return redirect()->route('admin.customers.show', $customer)->with('success', 'Cliente actualizado correctamente.');
    }

    public function toggleActive(CustomerProfile $customer): RedirectResponse
    {
        $customer->update(['is_active' => ! $customer->is_active]);
        $customer->user?->update(['is_active' => $customer->is_active]);

        AuditLogger::record($customer->is_active ? 'activated' : 'deactivated', 'customers', "Estado cliente: {$customer->email}");

        return back()->with('success', 'Estado del cliente actualizado.');
    }

    private function formData(CustomerProfile $customer): array
    {
        return [
            'customer' => $customer->loadMissing('tags'),
            'priceLists' => PriceList::query()->where('is_active', true)->orderBy('name')->get(),
            'customerTags' => CustomerTag::query()->where('is_active', true)->orderBy('name')->get(),
        ];
    }

    private function stats(): array
    {
        return [
            'total' => CustomerProfile::query()->count(),
            'active' => CustomerProfile::query()->where('is_active', true)->count(),
            'new' => CustomerProfile::query()->where('created_at', '>=', now()->subDays(30))->count(),
            'vip' => CustomerProfile::query()->whereHas('tags', fn ($query) => $query->where('slug', 'vip'))->count(),
            'companies' => CustomerProfile::query()->where('customer_type', 'company')->count(),
            'newsletter' => CustomerProfile::query()->where('newsletter', true)->count(),
            'inactive' => CustomerProfile::query()->where('is_active', false)->count(),
            'topBuyer' => null,
        ];
    }
}
