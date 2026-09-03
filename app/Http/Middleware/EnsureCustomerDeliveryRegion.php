<?php

namespace App\Http\Middleware;

use App\Services\DeliveryRegionService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerDeliveryRegion
{
    public function __construct(private DeliveryRegionService $deliveryRegions) {}

    public function handle(Request $request, Closure $next): Response
    {
        $region = $this->deliveryRegions->currentRegion($request->user());

        if ($region && array_key_exists($region, $this->deliveryRegions->regions())) {
            return $next($request);
        }

        $message = 'Antes de comprar debes configurar una dirección principal de despacho con una región válida.';

        if ($request->expectsJson()) {
            return new JsonResponse([
                'message' => $message,
                'redirect' => route('account.addresses'),
            ], 422);
        }

        return redirect()->route('account.addresses')->with('error', $message);
    }
}
