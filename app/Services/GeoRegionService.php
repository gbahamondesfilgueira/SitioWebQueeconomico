<?php

namespace App\Services;

use JsonException;
use RuntimeException;

class GeoRegionService
{
    private const REGION_CODES = [
        1 => 'tarapaca',
        2 => 'antofagasta',
        3 => 'atacama',
        4 => 'coquimbo',
        5 => 'valparaiso',
        6 => 'ohiggins',
        7 => 'maule',
        8 => 'biobio',
        9 => 'araucania',
        10 => 'los-lagos',
        11 => 'aysen',
        12 => 'magallanes',
        13 => DeliveryRegionService::CENTRAL_REGION,
        14 => 'los-rios',
        15 => 'arica-y-parinacota',
        16 => 'nuble',
    ];

    private ?array $features = null;

    public function regionForCoordinates(float $latitude, float $longitude): ?string
    {
        if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
            return null;
        }

        foreach ($this->features() as $feature) {
            if ($this->geometryContains($feature['geometry'] ?? [], $longitude, $latitude)) {
                $code = (int) ($feature['properties']['code'] ?? 0);

                return self::REGION_CODES[$code] ?? null;
            }
        }

        return null;
    }

    private function features(): array
    {
        if ($this->features !== null) {
            return $this->features;
        }

        $path = resource_path('data/chile-regions.geojson');
        $contents = is_file($path) ? file_get_contents($path) : false;

        if ($contents === false) {
            throw new RuntimeException('No se encontró la capa geográfica de regiones de Chile.');
        }

        try {
            $data = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException('La capa geográfica de regiones no es válida.', previous: $exception);
        }

        return $this->features = $data['features'] ?? [];
    }

    private function geometryContains(array $geometry, float $longitude, float $latitude): bool
    {
        $coordinates = $geometry['coordinates'] ?? [];

        return match ($geometry['type'] ?? null) {
            'Polygon' => $this->polygonContains($coordinates, $longitude, $latitude),
            'MultiPolygon' => collect($coordinates)->contains(
                fn (array $polygon) => $this->polygonContains($polygon, $longitude, $latitude)
            ),
            default => false,
        };
    }

    private function polygonContains(array $rings, float $longitude, float $latitude): bool
    {
        if ($rings === [] || ! $this->ringContains($rings[0], $longitude, $latitude)) {
            return false;
        }

        foreach (array_slice($rings, 1) as $hole) {
            if ($this->ringContains($hole, $longitude, $latitude)) {
                return false;
            }
        }

        return true;
    }

    private function ringContains(array $ring, float $longitude, float $latitude): bool
    {
        $inside = false;
        $count = count($ring);

        if ($count < 3) {
            return false;
        }

        for ($current = 0, $previous = $count - 1; $current < $count; $previous = $current++) {
            [$currentLongitude, $currentLatitude] = $ring[$current];
            [$previousLongitude, $previousLatitude] = $ring[$previous];

            if ($this->pointIsOnSegment(
                $longitude,
                $latitude,
                (float) $previousLongitude,
                (float) $previousLatitude,
                (float) $currentLongitude,
                (float) $currentLatitude,
            )) {
                return true;
            }

            $crossesLatitude = ($currentLatitude > $latitude) !== ($previousLatitude > $latitude);

            if ($crossesLatitude) {
                $intersection = ($previousLongitude - $currentLongitude)
                    * ($latitude - $currentLatitude)
                    / ($previousLatitude - $currentLatitude)
                    + $currentLongitude;

                if ($longitude < $intersection) {
                    $inside = ! $inside;
                }
            }
        }

        return $inside;
    }

    private function pointIsOnSegment(
        float $longitude,
        float $latitude,
        float $startLongitude,
        float $startLatitude,
        float $endLongitude,
        float $endLatitude,
    ): bool {
        $crossProduct = ($latitude - $startLatitude) * ($endLongitude - $startLongitude)
            - ($longitude - $startLongitude) * ($endLatitude - $startLatitude);

        if (abs($crossProduct) > 0.0000001) {
            return false;
        }

        return $longitude >= min($startLongitude, $endLongitude) - 0.0000001
            && $longitude <= max($startLongitude, $endLongitude) + 0.0000001
            && $latitude >= min($startLatitude, $endLatitude) - 0.0000001
            && $latitude <= max($startLatitude, $endLatitude) + 0.0000001;
    }
}
