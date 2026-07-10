<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Models\CustomerCompany;
use App\Models\CustomerProfile;
use App\Models\Order;
use App\Models\PriceList;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function dashboard(Request $request): View
    {
        $customer = $this->customerProfile($request)
            ->load(['wishlistItems.product.images', 'wishlistItems.variant'])
            ->loadCount(['addresses', 'companies', 'favorites', 'wishlistItems', 'documents']);

        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->limit(3)
            ->get();

        return view('account.dashboard', [
            'customer' => $customer,
            'orders' => $orders,
            'wishlistItems' => $customer->wishlistItems->take(6),
        ]);
    }

    public function profile(Request $request): View
    {
        return view('account.profile', ['customer' => $this->customerProfile($request), 'priceLists' => PriceList::query()->where('is_active', true)->get()]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $profile = $this->customerProfile($request);
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:50'],
            'newsletter' => ['nullable', 'boolean'],
            'accept_promotions' => ['nullable', 'boolean'],
            'accept_sms' => ['nullable', 'boolean'],
            'accept_whatsapp' => ['nullable', 'boolean'],
            'accept_email_marketing' => ['nullable', 'boolean'],
            'accept_cookies' => ['nullable', 'boolean'],
        ]);

        foreach (['newsletter', 'accept_promotions', 'accept_sms', 'accept_whatsapp', 'accept_email_marketing', 'accept_cookies'] as $field) {
            $data[$field] = $request->boolean($field);
        }

        if (collect($data)->only(['newsletter', 'accept_promotions', 'accept_sms', 'accept_whatsapp', 'accept_email_marketing', 'accept_cookies'])->contains(true)) {
            $data['consent_accepted_at'] = now();
        }

        $profile->update($data);
        $profile->user->update(['name' => trim($data['first_name'].' '.$data['last_name']), 'phone' => $data['phone'] ?? null]);
        AuditLogger::record('updated', 'customers', 'Cliente actualizó su perfil.');

        return back()->with('success', 'Perfil actualizado.');
    }

    public function addresses(Request $request): View
    {
        return view('account.addresses', ['customer' => $this->customerProfile($request)->load('addresses')]);
    }

    public function storeAddress(Request $request): RedirectResponse
    {
        $customer = $this->customerProfile($request);
        $data = $request->validate([
            'address_type' => ['required', 'in:billing,shipping,other'],
            'address_label' => ['required', 'in:main,office,home,pickup'],
            'contact_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:100'],
            'region' => ['required', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'commune' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:50'],
            'apartment' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:30'],
            'reference' => ['nullable', 'string', 'max:255'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $data['is_default'] = $request->boolean('is_default');
        $data['is_active'] = true;

        if ($data['is_default']) {
            $customer->addresses()->where('address_type', $data['address_type'])->update(['is_default' => false]);
        }

        $customer->addresses()->create($data);
        AuditLogger::record('created', 'customers', 'Cliente agrego una direccion.');

        return back()->with('success', 'Direccion agregada.');
    }

    public function companies(Request $request): View
    {
        return view('account.companies', ['customer' => $this->customerProfile($request)->load('companies.contacts')]);
    }

    public function storeCompany(Request $request): RedirectResponse
    {
        $customer = $this->customerProfile($request);
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'rut' => ['required', 'string', 'max:30'],
            'business_activity' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'website' => ['nullable', 'url', 'max:255'],
            'billing_email' => ['nullable', 'email', 'max:255'],
            'payment_terms' => ['nullable', 'string', 'max:255'],
            'credit_limit' => ['nullable', 'numeric', 'min:0'],
        ]);

        $data['is_active'] = true;
        $customer->companies()->create($data);
        AuditLogger::record('created', 'customers', 'Cliente agrego una empresa.');

        return back()->with('success', 'Empresa agregada.');
    }

    public function favorites(Request $request): View
    {
        return view('account.favorites', ['customer' => $this->customerProfile($request)->load('favorites.product', 'favorites.variant')]);
    }

    public function wishlist(Request $request): View
    {
        return view('account.wishlist', ['customer' => $this->customerProfile($request)->load('wishlistItems.product', 'wishlistItems.variant')]);
    }

    public function documents(Request $request): View
    {
        return view('account.documents', ['customer' => $this->customerProfile($request)->load('documents')]);
    }

    public function rewards(Request $request): View
    {
        return view('account.rewards', ['customer' => $this->customerProfile($request)->load('rewardTransactions')]);
    }

    public function coupons(): View
    {
        return view('account.coupons');
    }

    public function security(Request $request): View
    {
        return view('account.security', ['sessions' => collect(), 'twoFactorPrepared' => true]);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update(['password' => Hash::make($data['password'])]);
        AuditLogger::record('password_changed', 'customers', 'Cliente cambió su contraseña.');

        return back()->with('success', 'Contraseña actualizada.');
    }

    private function customerProfile(Request $request): CustomerProfile
    {
        return $request->user()->customerProfile()->firstOrCreate([
            'user_id' => $request->user()->id,
        ], [
            'customer_type' => 'individual',
            'first_name' => strtok($request->user()->name, ' ') ?: $request->user()->name,
            'last_name' => trim(str_replace(strtok($request->user()->name, ' ') ?: '', '', $request->user()->name)) ?: '-',
            'email' => $request->user()->email,
            'phone' => $request->user()->phone,
            'is_active' => true,
        ]);
    }
}
