<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Models\ShippingQuote;
use App\Services\AuditLogger;
use App\Services\CartService;
use App\Services\ShippingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(Request $request, CartService $cartService): View|RedirectResponse
    {
        $cart = $cartService->getOrCreateCart($request->user(), $request->session()->getId());
        if ($cart->items->isEmpty()) return redirect()->route('store.cart.index');
        AuditLogger::record('started', 'checkout', "Checkout iniciado #{$cart->id}");

        return view('store.checkout.index', $this->checkoutData($request, $cartService, 'customer'));
    }

    public function customer(Request $request, CartService $cartService): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'rut' => ['nullable', 'string', 'max:30'],
        ]);

        session(['checkout.customer' => $request->only(['first_name', 'last_name', 'email', 'phone', 'rut'])]);
        AuditLogger::record('customer_identified', 'checkout', 'Cliente identificado en checkout.');

        return redirect()->route('store.checkout.shipping-address');
    }

    public function shippingAddress(Request $request, CartService $cartService, ShippingService $shippingService): View|RedirectResponse
    {
        if ($request->isMethod('get')) {
            return view('store.checkout.index', $this->checkoutData($request, $cartService, 'shipping-address'));
        }

        $this->saveAddress($request, $cartService, 'shipping');
        $cart = $cartService->getOrCreateCart($request->user(), $request->session()->getId());
        $address = $cart->addresses()->where('address_type', 'shipping')->first();
        $shippingService->quoteCartShipping($cart, $address->only(['country', 'region', 'commune', 'city']));
        return redirect()->route('store.checkout.billing-address');
    }

    public function billingAddress(Request $request, CartService $cartService): View|RedirectResponse
    {
        if ($request->isMethod('get')) {
            return view('store.checkout.index', $this->checkoutData($request, $cartService, 'billing-address'));
        }

        if ($request->boolean('same_as_shipping')) {
            $cart = $cartService->getOrCreateCart($request->user(), $request->session()->getId());
            $shipping = $cart->addresses()->where('address_type', 'shipping')->latest()->first();
            if ($shipping) {
                $cart->addresses()->updateOrCreate(['address_type' => 'billing'], [...$shipping->only(['customer_address_id', 'contact_name', 'phone', 'email', 'country', 'region', 'commune', 'city', 'street', 'number', 'apartment', 'postal_code', 'reference']), 'address_type' => 'billing']);
            }
        } else {
            $this->saveAddress($request, $cartService, 'billing');
        }

        return redirect()->route('store.checkout.shipping-method');
    }

    public function shippingMethod(Request $request, CartService $cartService): View|RedirectResponse
    {
        if ($request->isMethod('get')) {
            return view('store.checkout.index', $this->checkoutData($request, $cartService, 'shipping-method'));
        }

        $data = $request->validate([
            'shipping_method' => ['required'],
        ]);
        $cart = $cartService->getOrCreateCart($request->user(), $request->session()->getId());
        if (str_starts_with($data['shipping_method'], 'quote:')) {
            $quote = ShippingQuote::query()->whereKey((int) str_replace('quote:', '', $data['shipping_method']))->where('cart_session_id', $cart->id)->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))->firstOrFail();
            $selected = ['shipping_quote_id' => $quote->id, 'carrier_name' => $quote->carrier->name, 'service_name' => $quote->service?->name ?? 'Servicio estándar', 'shipping_type' => $quote->service?->service_type === 'pickup' ? 'pickup' : 'delivery', 'estimated_price' => $quote->price, 'estimated_days' => $quote->estimated_days_max];
        } else {
            $options = $this->shippingOptions();
            $selected = $options[$data['shipping_method']];
        }
        $cart->shippingMethod()->delete();
        $cart->shippingMethod()->create($selected + ['status' => 'selected']);
        AuditLogger::record('shipping_selected', 'checkout', "Método envío: {$selected['service_name']}");

        return redirect()->route('store.checkout.payment-method');
    }

    public function paymentMethod(Request $request, CartService $cartService): View|RedirectResponse
    {
        if ($request->isMethod('get')) {
            return view('store.checkout.index', $this->checkoutData($request, $cartService, 'payment-method'));
        }

        $data = $request->validate([
            'payment_method' => ['required', Rule::in(['bank_transfer', 'cash_on_delivery', 'webpay_ready', 'mercado_pago_ready'])],
        ]);
        $options = $this->paymentOptions();
        $cart = $cartService->getOrCreateCart($request->user(), $request->session()->getId());
        $cart->paymentMethod()->delete();
        $cart->paymentMethod()->create($options[$data['payment_method']] + ['status' => 'selected']);
        AuditLogger::record('payment_selected', 'checkout', "Método pago: {$options[$data['payment_method']]['payment_label']}");

        return redirect()->route('store.checkout.review');
    }

    public function review(Request $request, CartService $cartService): View
    {
        return view('store.checkout.index', $this->checkoutData($request, $cartService, 'review'));
    }

    private function saveAddress(Request $request, CartService $cartService, string $type): void
    {
        $data = $request->validate([
            'customer_address_id' => ['nullable', 'exists:customer_addresses,id'],
            'contact_name' => ['required_without:customer_address_id', 'string', 'max:255'],
            'phone' => ['required_without:customer_address_id', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'country' => ['required_without:customer_address_id', 'string', 'max:100'],
            'region' => ['required_without:customer_address_id', 'string', 'max:100'],
            'commune' => ['required_without:customer_address_id', 'string', 'max:100'],
            'city' => ['required_without:customer_address_id', 'string', 'max:100'],
            'street' => ['required_without:customer_address_id', 'string', 'max:255'],
            'number' => ['required_without:customer_address_id', 'string', 'max:50'],
            'apartment' => ['nullable', 'string', 'max:50'],
            'postal_code' => ['nullable', 'string', 'max:30'],
            'reference' => ['nullable', 'string'],
        ]);
        $cart = $cartService->getOrCreateCart($request->user(), $request->session()->getId());

        if (! empty($data['customer_address_id'])) {
            $address = CustomerAddress::query()->whereKey($data['customer_address_id'])->firstOrFail();
            $data = [
                'customer_address_id' => $address->id,
                'contact_name' => $address->contact_name,
                'phone' => $address->phone,
                'country' => $address->country,
                'region' => $address->region,
                'commune' => $address->commune,
                'city' => $address->city,
                'street' => $address->street,
                'number' => $address->number,
                'apartment' => $address->apartment,
                'postal_code' => $address->postal_code,
                'reference' => $address->reference,
            ];
        }

        DB::transaction(function () use ($cart, $type, $data) {
            $cart->addresses()->updateOrCreate(['address_type' => $type], ['address_type' => $type] + $data);
        });
        AuditLogger::record('address_saved', 'checkout', "Dirección {$type} ingresada.");
    }

    private function checkoutData(Request $request, CartService $cartService, string $step): array
    {
        $cart = $cartService->getOrCreateCart($request->user(), $request->session()->getId());
        $cartService->recalculateCart($cart);
        $cart->load('items.product.images', 'items.variant', 'items.pack', 'addresses', 'shippingMethod', 'paymentMethod', 'coupons');

        return [
            'cart' => $cart,
            'summary' => $cartService->getCartSummary($cart),
            'step' => $step,
            'customer' => $request->user()?->customerProfile,
            'savedAddresses' => $request->user()?->customerProfile?->addresses()->where('is_active', true)->get() ?? collect(),
            'companies' => $request->user()?->customerProfile?->companies()->where('is_active', true)->get() ?? collect(),
            'shippingOptions' => $this->shippingOptions(),
            'shippingQuotes' => $cart->shippingMethod?->quote ? collect([$cart->shippingMethod->quote]) : \App\Models\ShippingQuote::query()->with('carrier', 'service')->where('cart_session_id', $cart->id)->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))->latest()->limit(10)->get(),
            'paymentOptions' => $this->paymentOptions(),
            'seo' => ['title' => 'Checkout'],
        ];
    }

    private function shippingOptions(): array
    {
        return [
            'pickup' => ['service_name' => 'Retiro en tienda', 'shipping_type' => 'pickup', 'estimated_price' => 0, 'estimated_days' => 1, 'carrier_name' => null],
            'standard' => ['service_name' => 'Envío estándar', 'shipping_type' => 'delivery', 'estimated_price' => 3990, 'estimated_days' => 3, 'carrier_name' => 'Qué Económico'],
            'pay_on_delivery' => ['service_name' => 'Envío por pagar', 'shipping_type' => 'delivery', 'estimated_price' => 0, 'estimated_days' => 4, 'carrier_name' => 'Por definir'],
            'fixed' => ['service_name' => 'Envío fijo temporal', 'shipping_type' => 'delivery', 'estimated_price' => 2990, 'estimated_days' => 2, 'carrier_name' => 'Qué Económico'],
        ];
    }

    private function paymentOptions(): array
    {
        return [
            'bank_transfer' => ['payment_method' => 'bank_transfer', 'payment_label' => 'Transferencia bancaria'],
            'cash_on_delivery' => ['payment_method' => 'cash_on_delivery', 'payment_label' => 'Pago contra entrega'],
            'webpay_ready' => ['payment_method' => 'webpay_ready', 'payment_label' => 'Webpay preparado'],
            'mercado_pago_ready' => ['payment_method' => 'mercado_pago_ready', 'payment_label' => 'Mercado Pago preparado'],
        ];
    }
}
