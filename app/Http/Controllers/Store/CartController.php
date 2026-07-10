<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request, CartService $cartService): View
    {
        $cart = $cartService->getOrCreateCart($request->user(), $request->session()->getId());
        $summary = $cartService->getCartSummary($cart);

        return $cart->items->isEmpty()
            ? view('store.cart.empty', ['seo' => ['title' => 'Carrito vacío']])
            : view('store.cart.index', compact('cart', 'summary') + ['seo' => ['title' => 'Carrito']]);
    }

    public function add(Request $request, CartService $cartService): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'item_type' => ['required', Rule::in(['product', 'pack'])],
            'product_id' => ['nullable', 'required_if:item_type,product', 'exists:products,id'],
            'product_variant_id' => ['nullable', 'exists:product_variants,id'],
            'product_pack_id' => ['nullable', 'required_if:item_type,pack', 'exists:product_packs,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $cartService->getOrCreateCart($request->user(), $request->session()->getId());

        if ($data['item_type'] === 'pack') {
            $cartService->addPack($cart, (int) $data['product_pack_id'], (int) $data['quantity']);
        } else {
            $cartService->addProduct($cart, (int) $data['product_id'], ! empty($data['product_variant_id']) ? (int) $data['product_variant_id'] : null, (int) $data['quantity']);
        }

        $cart->load('items');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Producto agregado al carrito.',
                'cart_count' => (int) $cart->items->sum('quantity'),
            ]);
        }

        return back()->with('success', 'Producto agregado al carrito.');
    }

    public function update(Request $request, CartService $cartService): RedirectResponse
    {
        $data = $request->validate([
            'cart_item_id' => ['required', 'exists:cart_items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $item = $this->ownedItem($request, (int) $data['cart_item_id'], $cartService);
        $cartService->updateItemQuantity($item, (int) $data['quantity']);

        return back()->with('success', 'Cantidad actualizada.');
    }

    public function remove(Request $request, CartService $cartService): RedirectResponse
    {
        $data = $request->validate(['cart_item_id' => ['required', 'exists:cart_items,id']]);
        $cartService->removeItem($this->ownedItem($request, (int) $data['cart_item_id'], $cartService));

        return back()->with('success', 'Item eliminado.');
    }

    public function clear(Request $request, CartService $cartService): RedirectResponse
    {
        $cart = $cartService->getOrCreateCart($request->user(), $request->session()->getId());
        $cartService->clearCart($cart);

        return redirect()->route('store.cart.index')->with('success', 'Carrito vaciado.');
    }

    private function ownedItem(Request $request, int $itemId, CartService $cartService): CartItem
    {
        $cart = $cartService->getOrCreateCart($request->user(), $request->session()->getId());
        return $cart->items()->whereKey($itemId)->firstOrFail();
    }
}
