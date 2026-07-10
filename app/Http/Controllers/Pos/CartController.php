<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pos\Concerns\ResolvesPosTerminal;
use App\Models\PosCartItem;
use App\Services\PosService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ResolvesPosTerminal;

    public function add(Request $request, PosService $posService)
    {
        $data = $request->validate([
            'product_id' => ['nullable', 'integer', 'required_without:pack_id'],
            'variant_id' => ['nullable', 'integer'],
            'pack_id' => ['nullable', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);
        $cart = $posService->getActiveCart($this->terminal($request)->id, $request->user()->id);
        isset($data['pack_id'])
            ? $posService->addPack($cart, (int) $data['pack_id'], (int) $data['quantity'])
            : $posService->addProductById($cart, (int) $data['product_id'], $data['variant_id'] ?? null, (int) $data['quantity']);

        return back()->with('success', 'Ítem agregado al POS.');
    }

    public function update(Request $request, PosService $posService)
    {
        $data = $request->validate(['item_id' => ['required', 'integer'], 'quantity' => ['required', 'integer', 'min:1']]);
        $item = PosCartItem::query()->whereKey($data['item_id'])->whereHas('cart', fn ($q) => $q->where('user_id', $request->user()->id)->where('status', 'active'))->firstOrFail();
        $posService->updateQuantity($item, (int) $data['quantity']);
        return back()->with('success', 'Cantidad actualizada.');
    }

    public function remove(Request $request, PosService $posService)
    {
        $data = $request->validate(['item_id' => ['required', 'integer']]);
        $item = PosCartItem::query()->whereKey($data['item_id'])->whereHas('cart', fn ($q) => $q->where('user_id', $request->user()->id)->where('status', 'active'))->firstOrFail();
        $posService->removeItem($item);
        return back()->with('success', 'Ítem eliminado.');
    }

    public function clear(Request $request, PosService $posService)
    {
        $posService->clearCart($posService->getActiveCart($this->terminal($request)->id, $request->user()->id));
        return back()->with('success', 'Carrito POS vaciado.');
    }
}
