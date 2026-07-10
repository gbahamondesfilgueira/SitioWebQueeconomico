<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockAdjustment;
use App\Models\StockLevel;
use App\Models\StockMovement;
use App\Models\StockReservation;
use App\Models\StockTransfer;
use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryService
{
    public function getStockLevel(int $warehouseId, int $productId, ?int $variantId = null, ?int $locationId = null): StockLevel
    {
        $this->validateReferences($warehouseId, $productId, $variantId, $locationId);

        return StockLevel::query()->firstOrCreate([
            'warehouse_id' => $warehouseId,
            'warehouse_location_id' => $locationId,
            'product_id' => $productId,
            'product_variant_id' => $variantId,
        ], [
            'physical_stock' => 0,
            'reserved_stock' => 0,
            'minimum_stock' => 0,
        ]);
    }

    public function getAvailableStock(int $warehouseId, int $productId, ?int $variantId = null, ?int $locationId = null): int
    {
        $level = $this->getStockLevel($warehouseId, $productId, $variantId, $locationId);

        return $level->available_stock;
    }

    public function increaseStock(int $warehouseId, int $productId, ?int $variantId, ?int $locationId, float $quantity, string $movementType, ?string $notes = null, ?string $referenceType = null, ?int $referenceId = null): StockLevel
    {
        return DB::transaction(function () use ($warehouseId, $productId, $variantId, $locationId, $quantity, $movementType, $notes, $referenceType, $referenceId) {
            $quantity = $this->normalizeQuantity($quantity);
            $level = $this->getStockLevel($warehouseId, $productId, $variantId, $locationId);
            $previous = (float) $level->physical_stock;
            $level->physical_stock = $previous + $quantity;
            $level->save();
            $this->createMovement($warehouseId, $productId, $variantId, $locationId, $movementType, $quantity, $previous, (float) $level->physical_stock, $referenceType, $referenceId, $notes);
            return $level;
        });
    }

    public function decreaseStock(int $warehouseId, int $productId, ?int $variantId, ?int $locationId, float $quantity, string $movementType, ?string $notes = null, ?string $referenceType = null, ?int $referenceId = null): StockLevel
    {
        return DB::transaction(function () use ($warehouseId, $productId, $variantId, $locationId, $quantity, $movementType, $notes, $referenceType, $referenceId) {
            $quantity = $this->normalizeQuantity($quantity);
            $level = $this->getStockLevel($warehouseId, $productId, $variantId, $locationId);
            if ($level->available_stock < $quantity) {
                throw ValidationException::withMessages(['quantity' => 'Stock disponible insuficiente.']);
            }
            $previous = (float) $level->physical_stock;
            $level->physical_stock = $previous - $quantity;
            $level->save();
            $this->createMovement($warehouseId, $productId, $variantId, $locationId, $movementType, $quantity, $previous, (float) $level->physical_stock, $referenceType, $referenceId, $notes);
            return $level;
        });
    }

    public function reserveStock(int $warehouseId, int $productId, ?int $variantId, float $quantity, ?string $referenceType = null, ?int $referenceId = null, $expiresAt = null, ?int $locationId = null): StockReservation
    {
        return DB::transaction(function () use ($warehouseId, $productId, $variantId, $quantity, $referenceType, $referenceId, $expiresAt, $locationId) {
            $quantity = $this->normalizeQuantity($quantity);
            $locationId ??= $this->findAvailableLocationId($warehouseId, $productId, $variantId, $quantity);
            $level = $this->getStockLevel($warehouseId, $productId, $variantId, $locationId);
            if ($level->available_stock < $quantity) {
                throw ValidationException::withMessages(['quantity' => 'Stock disponible insuficiente para reservar.']);
            }
            $previous = (float) $level->physical_stock;
            $level->reserved_stock = (float) $level->reserved_stock + $quantity;
            $level->save();
            $reservation = StockReservation::query()->create([
                'warehouse_id' => $warehouseId,
                'warehouse_location_id' => $locationId,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'quantity' => $quantity,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'expires_at' => $expiresAt,
                'status' => 'active',
                'user_id' => Auth::id(),
            ]);
            $this->createMovement($warehouseId, $productId, $variantId, $locationId, 'reservation', $quantity, $previous, $previous, $referenceType, $referenceId, 'Reserva de stock');
            AuditLogger::record('created', 'stock_reservations', "Reserva creada #{$reservation->id}");
            return $reservation;
        });
    }

    public function releaseReservation(StockReservation $reservation): void
    {
        DB::transaction(function () use ($reservation): void {
            if ($reservation->status !== 'active') {
                throw ValidationException::withMessages(['reservation' => 'La reserva no está activa.']);
            }
            $level = $this->getStockLevel($reservation->warehouse_id, $reservation->product_id, $reservation->product_variant_id, $reservation->warehouse_location_id);
            $level->reserved_stock = max(0, (float) $level->reserved_stock - (float) $reservation->quantity);
            $level->save();
            $reservation->update(['status' => 'released']);
            $this->createMovement($reservation->warehouse_id, $reservation->product_id, $reservation->product_variant_id, $reservation->warehouse_location_id, 'reservation_release', (float) $reservation->quantity, (float) $level->physical_stock, (float) $level->physical_stock, $reservation->reference_type, $reservation->reference_id, 'Liberación de reserva');
            AuditLogger::record('released', 'stock_reservations', "Reserva liberada #{$reservation->id}");
        });
    }

    public function consumeReservation(StockReservation $reservation): void
    {
        DB::transaction(function () use ($reservation): void {
            if ($reservation->status !== 'active') {
                throw ValidationException::withMessages(['reservation' => 'La reserva no está activa.']);
            }
            $level = $this->getStockLevel($reservation->warehouse_id, $reservation->product_id, $reservation->product_variant_id, $reservation->warehouse_location_id);
            $previous = (float) $level->physical_stock;
            $level->reserved_stock = max(0, (float) $level->reserved_stock - (float) $reservation->quantity);
            $level->physical_stock = $previous - (float) $reservation->quantity;
            if ((float) $level->physical_stock < 0) {
                throw ValidationException::withMessages(['reservation' => 'La reserva dejaría stock físico negativo.']);
            }
            $level->save();
            $reservation->update(['status' => 'consumed']);
            $this->createMovement($reservation->warehouse_id, $reservation->product_id, $reservation->product_variant_id, $reservation->warehouse_location_id, 'sale_out', (float) $reservation->quantity, $previous, (float) $level->physical_stock, $reservation->reference_type, $reservation->reference_id, 'Consumo de reserva');
            AuditLogger::record('consumed', 'stock_reservations', "Reserva consumida #{$reservation->id}");
        });
    }

    public function createMovement(int $warehouseId, int $productId, ?int $variantId, ?int $locationId, string $type, float $quantity, float $previous, float $new, ?string $referenceType = null, ?int $referenceId = null, ?string $notes = null): StockMovement
    {
        return StockMovement::query()->create([
            'warehouse_id' => $warehouseId,
            'warehouse_location_id' => $locationId,
            'product_id' => $productId,
            'product_variant_id' => $variantId,
            'movement_type' => $type,
            'quantity' => $quantity,
            'previous_stock' => $previous,
            'new_stock' => $new,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'notes' => $notes,
            'user_id' => Auth::id(),
        ]);
    }

    public function approveAdjustment(StockAdjustment $adjustment): void
    {
        DB::transaction(function () use ($adjustment): void {
            if ($adjustment->status !== 'pending') {
                throw ValidationException::withMessages(['status' => 'Solo se pueden aprobar ajustes pendientes.']);
            }
            $type = $adjustment->adjustment_type === 'increase' ? 'adjustment_in' : 'adjustment_out';
            if ($adjustment->adjustment_type === 'increase') {
                $this->increaseStock($adjustment->warehouse_id, $adjustment->product_id, $adjustment->product_variant_id, $adjustment->warehouse_location_id, (float) $adjustment->quantity, $type, $adjustment->notes, StockAdjustment::class, $adjustment->id);
            } else {
                $this->decreaseStock($adjustment->warehouse_id, $adjustment->product_id, $adjustment->product_variant_id, $adjustment->warehouse_location_id, (float) $adjustment->quantity, $type, $adjustment->notes, StockAdjustment::class, $adjustment->id);
            }
            $adjustment->update(['status' => 'approved', 'approved_by' => Auth::id(), 'approved_at' => now()]);
            AuditLogger::record('approved', 'stock_adjustments', "Ajuste aprobado #{$adjustment->id}");
        });
    }

    public function sendTransfer(StockTransfer $transfer): void
    {
        DB::transaction(function () use ($transfer): void {
            if (! in_array($transfer->status, ['draft', 'pending'], true)) {
                throw ValidationException::withMessages(['status' => 'No se puede enviar esta transferencia.']);
            }
            if ($transfer->origin_warehouse_id === $transfer->destination_warehouse_id) {
                throw ValidationException::withMessages(['destination_warehouse_id' => 'Origen y destino no pueden ser la misma bodega.']);
            }
            foreach ($transfer->items as $item) {
                $this->decreaseStock($transfer->origin_warehouse_id, $item->product_id, $item->product_variant_id, $transfer->origin_location_id, (float) $item->quantity, 'transfer_out', $transfer->notes, StockTransfer::class, $transfer->id);
            }
            $transfer->update(['status' => 'in_transit', 'sent_by' => Auth::id(), 'sent_at' => now()]);
            AuditLogger::record('sent', 'stock_transfers', "Transferencia enviada #{$transfer->id}");
        });
    }

    public function receiveTransfer(StockTransfer $transfer): void
    {
        DB::transaction(function () use ($transfer): void {
            if ($transfer->status !== 'in_transit') {
                throw ValidationException::withMessages(['status' => 'Solo se reciben transferencias en tránsito.']);
            }
            foreach ($transfer->items as $item) {
                $this->increaseStock($transfer->destination_warehouse_id, $item->product_id, $item->product_variant_id, $transfer->destination_location_id, (float) $item->quantity, 'transfer_in', $transfer->notes, StockTransfer::class, $transfer->id);
                $item->update(['received_quantity' => $item->quantity]);
            }
            $transfer->update(['status' => 'received', 'received_by' => Auth::id(), 'received_at' => now()]);
            AuditLogger::record('received', 'stock_transfers', "Transferencia recibida #{$transfer->id}");
        });
    }

    private function validateReferences(int $warehouseId, int $productId, ?int $variantId, ?int $locationId): void
    {
        if (! Warehouse::query()->whereKey($warehouseId)->where('is_active', true)->exists()) throw ValidationException::withMessages(['warehouse_id' => 'Bodega inactiva o no encontrada.']);
        if (! Product::query()->whereKey($productId)->where('is_active', true)->exists()) throw ValidationException::withMessages(['product_id' => 'Producto inactivo o no encontrado.']);
        if ($variantId && ! ProductVariant::query()->whereKey($variantId)->where('product_id', $productId)->where('is_active', true)->exists()) throw ValidationException::withMessages(['product_variant_id' => 'Variante inválida o inactiva.']);
        if ($locationId && ! WarehouseLocation::query()->whereKey($locationId)->where('warehouse_id', $warehouseId)->where('is_active', true)->exists()) throw ValidationException::withMessages(['warehouse_location_id' => 'Ubicación inválida para la bodega.']);
    }

    private function normalizeQuantity(float $quantity): int
    {
        if ($quantity < 1 || floor($quantity) !== $quantity) {
            throw ValidationException::withMessages(['quantity' => 'La cantidad debe ser un numero entero mayor a cero.']);
        }

        return (int) $quantity;
    }

    private function findAvailableLocationId(int $warehouseId, int $productId, ?int $variantId, int $quantity): ?int
    {
        $level = StockLevel::query()
            ->where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->whereRaw('(physical_stock - reserved_stock) >= ?', [$quantity])
            ->orderByRaw('warehouse_location_id is null')
            ->orderBy('warehouse_location_id')
            ->first();

        return $level?->warehouse_location_id;
    }
}
