<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pos\Concerns\ResolvesPosTerminal;
use App\Services\PosService;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    use ResolvesPosTerminal;

    public function add(Request $request, PosService $posService)
    {
        $data = $request->validate(['barcode' => ['required', 'string', 'max:120'], 'quantity' => ['nullable', 'integer', 'min:1']]);
        $cart = $posService->getActiveCart($this->terminal($request)->id, $request->user()->id);
        $posService->addProductByBarcode($cart, $data['barcode'], (int) ($data['quantity'] ?? 1));
        return back()->with('success', 'Producto agregado por código de barras.');
    }
}
