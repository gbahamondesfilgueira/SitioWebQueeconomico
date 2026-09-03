<?php

namespace App\Services;

use App\Models\StockLevel;
use App\Models\StockReservation;
use App\Models\SystemHealthCheck;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockReconciliationService
{
    private const TOLERANCE = 0.001;

    public function scan(): Collection
    {
        $reservationTotals = StockReservation::query()
            ->where('status', 'active')
            ->select([
                'warehouse_id',
                'warehouse_location_id',
                'product_id',
                'product_variant_id',
                DB::raw('SUM(quantity) as reserved_total'),
            ])
            ->groupBy('warehouse_id', 'warehouse_location_id', 'product_id', 'product_variant_id')
            ->get()
            ->keyBy(fn (StockReservation $reservation) => StockLevel::sourceKey(
                $reservation->warehouse_id,
                $reservation->warehouse_location_id,
                $reservation->product_id,
                $reservation->product_variant_id,
            ));

        $issues = collect();

        StockLevel::query()->orderBy('id')->each(function (StockLevel $level) use ($reservationTotals, $issues): void {
            $expectedKey = StockLevel::sourceKey($level->warehouse_id, $level->warehouse_location_id, $level->product_id, $level->product_variant_id);
            $expectedReserved = (float) ($reservationTotals->pull($expectedKey)?->reserved_total ?? 0);
            $physical = (float) $level->physical_stock;
            $recordedReserved = (float) $level->reserved_stock;
            $types = [];

            if (abs($recordedReserved - $expectedReserved) > self::TOLERANCE) {
                $types[] = 'reservation_counter_mismatch';
            }
            if ($physical < 0 || $recordedReserved < 0) {
                $types[] = 'negative_stock';
            }
            if ($expectedReserved - $physical > self::TOLERANCE) {
                $types[] = 'overcommitted_stock';
            }
            if ($level->source_key !== $expectedKey) {
                $types[] = 'invalid_source_key';
            }

            if ($types !== []) {
                $issues->push([
                    'types' => $types,
                    'stock_level_id' => $level->id,
                    'source_key' => $expectedKey,
                    'warehouse_id' => $level->warehouse_id,
                    'warehouse_location_id' => $level->warehouse_location_id,
                    'product_id' => $level->product_id,
                    'product_variant_id' => $level->product_variant_id,
                    'physical_stock' => $physical,
                    'recorded_reserved' => $recordedReserved,
                    'expected_reserved' => $expectedReserved,
                ]);
            }
        });

        $reservationTotals->each(function (StockReservation $reservation, string $sourceKey) use ($issues): void {
            $issues->push([
                'types' => ['orphan_reservation_source'],
                'stock_level_id' => null,
                'source_key' => $sourceKey,
                'warehouse_id' => $reservation->warehouse_id,
                'warehouse_location_id' => $reservation->warehouse_location_id,
                'product_id' => $reservation->product_id,
                'product_variant_id' => $reservation->product_variant_id,
                'physical_stock' => null,
                'recorded_reserved' => null,
                'expected_reserved' => (float) $reservation->reserved_total,
            ]);
        });

        $expired = StockReservation::query()
            ->where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->count();

        if ($expired > 0) {
            $issues->push([
                'types' => ['expired_active_reservations'],
                'expired_reservations' => $expired,
            ]);
        }

        return $issues;
    }

    public function reconcile(bool $fixCounters = false, bool $releaseExpired = false): array
    {
        $before = $this->scan();
        $fixed = 0;
        $released = 0;

        if ($fixCounters) {
            foreach ($before->whereNotNull('stock_level_id') as $issue) {
                if (! in_array('reservation_counter_mismatch', $issue['types'], true)) {
                    continue;
                }

                DB::transaction(function () use ($issue, &$fixed): void {
                    $level = StockLevel::query()->whereKey($issue['stock_level_id'])->lockForUpdate()->firstOrFail();
                    $expected = (float) StockReservation::query()
                        ->where('status', 'active')
                        ->where('warehouse_id', $level->warehouse_id)
                        ->where('warehouse_location_id', $level->warehouse_location_id)
                        ->where('product_id', $level->product_id)
                        ->where('product_variant_id', $level->product_variant_id)
                        ->sum('quantity');

                    $level->forceFill(['reserved_stock' => $expected])->save();
                    $fixed++;
                });
            }
        }

        if ($releaseExpired) {
            StockReservation::query()
                ->where('status', 'active')
                ->whereNotNull('expires_at')
                ->where('expires_at', '<', now())
                ->orderBy('id')
                ->each(function (StockReservation $reservation) use (&$released): void {
                    app(InventoryService::class)->releaseReservation($reservation);
                    $released++;
                });
        }

        $after = ($fixCounters || $releaseExpired) ? $this->scan() : $before;
        $health = $this->healthCheck($after);
        SystemHealthCheck::query()->create($health + ['checked_at' => now()]);

        if ($health['status'] !== 'ok') {
            Log::warning('Conciliación de stock detectó inconsistencias.', $health['metadata']);
        }

        if ($fixed > 0 || $released > 0) {
            AuditLogger::record('reconciled', 'inventory', "Conciliación de stock: {$fixed} contador(es) corregido(s), {$released} reserva(s) vencida(s) liberada(s).");
        }

        return ['before' => $before, 'after' => $after, 'fixed' => $fixed, 'released' => $released, 'health' => $health];
    }

    public function healthCheck(?Collection $issues = null): array
    {
        $issues ??= $this->scan();
        $critical = $issues->contains(fn (array $issue) => collect($issue['types'])->intersect(['negative_stock', 'overcommitted_stock', 'orphan_reservation_source'])->isNotEmpty());
        $status = $issues->isEmpty() ? 'ok' : ($critical ? 'error' : 'warning');
        $message = $issues->isEmpty()
            ? 'Stock físico y reservas conciliados'
            : $issues->count().' inconsistencia(s) de stock detectada(s)';

        return [
            'check_name' => 'Conciliación de stock',
            'status' => $status,
            'message' => $message,
            'metadata' => [
                'issue_count' => $issues->count(),
                'issues' => $issues->take(50)->values()->all(),
                'truncated' => $issues->count() > 50,
            ],
        ];
    }
}
