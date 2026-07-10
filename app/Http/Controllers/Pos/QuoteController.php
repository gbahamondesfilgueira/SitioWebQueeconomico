<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pos\Concerns\ResolvesPosTerminal;
use App\Models\PosQuote;
use App\Services\PosService;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    use ResolvesPosTerminal;

    public function index()
    {
        return view('pos.quotes.index', ['quotes' => PosQuote::query()->with(['customer', 'terminal'])->latest()->paginate(20)]);
    }

    public function createFromCart(Request $request, PosService $posService)
    {
        $cart = $posService->getActiveCart($this->terminal($request)->id, $request->user()->id);
        $quote = $posService->createQuoteFromCart($cart);
        return redirect()->route('pos.quotes.show', $quote)->with('success', 'Cotización creada.');
    }

    public function show(PosQuote $quote)
    {
        return view('pos.quotes.show', ['quote' => $quote->load(['items.product', 'items.variant', 'items.pack', 'customer', 'terminal'])]);
    }

    public function convert(PosQuote $quote, PosService $posService)
    {
        $posService->convertQuoteToCart($quote);
        return redirect()->route('pos.sale.create')->with('success', 'Cotización convertida a venta POS.');
    }
}
