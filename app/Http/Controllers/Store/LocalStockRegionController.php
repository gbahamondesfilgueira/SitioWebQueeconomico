<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Services\DeliveryRegionService;
use App\Services\GeoRegionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocalStockRegionController extends Controller
{
    public function store(
        Request $request,
        GeoRegionService $geoRegions,
        DeliveryRegionService $deliveryRegions,
    ): JsonResponse {
        $data = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'accuracy' => ['nullable', 'numeric', 'min:0', 'max:100000'],
        ]);

        $region = $geoRegions->regionForCoordinates(
            (float) $data['latitude'],
            (float) $data['longitude'],
        );

        if (! $region) {
            return response()->json([
                'message' => 'No pudimos determinar una región chilena con esta ubicación.',
            ], 422);
        }

        $deliveryRegions->rememberLocalStockRegion($region);
        $accountRegion = $deliveryRegions->currentRegion($request->user());

        return response()->json([
            'message' => 'Stock local actualizado.',
            'region' => $region,
            'region_label' => $deliveryRegions->label($region),
            'account_region' => $accountRegion,
            'differs_from_account' => $accountRegion !== null && $accountRegion !== $region,
        ]);
    }
}
