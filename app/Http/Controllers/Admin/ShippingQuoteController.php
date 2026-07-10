<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditLogger;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShippingQuoteController extends Controller
{
    public function __invoke(Request $request, ShippingService $shipping): View
    {
        $quotes = collect();
        if ($request->filled('country')) {
            $data = $request->validate(['country' => ['required'], 'region' => ['required'], 'commune' => ['required'], 'city' => ['nullable'], 'weight' => ['required', 'numeric', 'min:0'], 'height' => ['nullable', 'numeric', 'min:0'], 'width' => ['nullable', 'numeric', 'min:0'], 'length' => ['nullable', 'numeric', 'min:0']]);
            $billable = max((float) $data['weight'], (($data['height'] ?? 0) * ($data['width'] ?? 0) * ($data['length'] ?? 0)) / 4000);
            $quotes = $shipping->findAvailableRates($data, $billable, $data);
            AuditLogger::record('calculated', 'shipping_quotes', 'Cotización admin calculada');
        }
        return view('admin.shipping.quote-calculator', compact('quotes'));
    }
}
