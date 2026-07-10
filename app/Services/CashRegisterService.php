<?php

namespace App\Services;

use App\Models\CashMovement;
use App\Models\CashRegisterSession;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\PosPaymentMethod;
use App\Models\PosRefund;
use App\Models\PosSaleCancellation;
use App\Models\PosTerminal;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CashRegisterService
{
    public function __construct(private InventoryService $inventoryService) {}

    public function openSession(int $terminalId, int $userId, float $openingAmount, ?string $notes = null): CashRegisterSession
    {
        return DB::transaction(function () use ($terminalId, $userId, $openingAmount, $notes) {
            if ($openingAmount < 0) throw ValidationException::withMessages(['opening_amount' => 'El monto inicial no puede ser negativo.']);
            $terminal = PosTerminal::query()->whereKey($terminalId)->where('is_active', true)->lockForUpdate()->firstOrFail();
            if ($this->getOpenSession($terminal->id)) throw ValidationException::withMessages(['terminal' => 'Ya existe una caja abierta para este terminal.']);

            $session = CashRegisterSession::query()->create([
                'pos_terminal_id' => $terminal->id,
                'opened_by' => $userId,
                'status' => 'open',
                'opening_amount' => $openingAmount,
                'expected_cash_amount' => $openingAmount,
                'opened_at' => now(),
                'notes' => $notes,
            ]);
            $this->createMovement($session, 'opening', $openingAmount, null, 'Apertura de caja', CashRegisterSession::class, $session->id, $userId);
            AuditLogger::record('opened', 'cash_register_sessions', "Caja abierta #{$session->id}", userId: $userId);

            return $session->load(['terminal', 'opener', 'movements.paymentMethod']);
        });
    }

    public function getOpenSession(int $terminalId): ?CashRegisterSession
    {
        return CashRegisterSession::query()
            ->where('pos_terminal_id', $terminalId)
            ->where('status', 'open')
            ->latest('opened_at')
            ->first();
    }

    public function requireOpenSession(int $terminalId): CashRegisterSession
    {
        $session = $this->getOpenSession($terminalId);
        if (! $session) throw ValidationException::withMessages(['cash_register' => 'Debes abrir caja antes de vender en este terminal.']);
        return $session;
    }

    public function registerSalePayment(Order $order, OrderPayment $payment): CashMovement
    {
        $session = $order->cashRegisterSession ?: $this->requireOpenSession((int) $order->pos_terminal_id);
        $method = PosPaymentMethod::query()->where('code', $payment->payment_method)->first();

        $movement = $this->createMovement(
            $session,
            'sale',
            (float) $payment->amount,
            $method?->id,
            "Venta POS {$order->order_number} - {$payment->payment_label}",
            OrderPayment::class,
            $payment->id,
            (int) ($order->sold_by ?? Auth::id()),
        );
        AuditLogger::record('sale_linked', 'cash_movements', "Venta {$order->order_number} asociada a caja #{$session->id}");

        return $movement;
    }

    public function registerIncome(CashRegisterSession $session, float $amount, string $description, ?int $paymentMethodId = null): CashMovement
    {
        $this->assertOpen($session);
        if ($amount <= 0) throw ValidationException::withMessages(['amount' => 'El monto debe ser mayor a cero.']);
        if (blank($description)) throw ValidationException::withMessages(['description' => 'La descripción es obligatoria.']);
        $movement = $this->createMovement($session, 'income', $amount, $paymentMethodId, $description, null, null, Auth::id() ?? $session->opened_by);
        AuditLogger::record('income_created', 'cash_movements', "Ingreso de caja #{$session->id}");
        return $movement;
    }

    public function registerExpense(CashRegisterSession $session, float $amount, string $description, ?int $paymentMethodId = null): CashMovement
    {
        $this->assertOpen($session);
        if ($amount <= 0) throw ValidationException::withMessages(['amount' => 'El monto debe ser mayor a cero.']);
        if (blank($description)) throw ValidationException::withMessages(['description' => 'La descripción es obligatoria.']);
        $movement = $this->createMovement($session, 'expense', $amount, $paymentMethodId, $description, null, null, Auth::id() ?? $session->opened_by);
        AuditLogger::record('expense_created', 'cash_movements', "Egreso de caja #{$session->id}");
        return $movement;
    }

    public function registerWithdrawal(CashRegisterSession $session, float $amount, string $description): CashMovement
    {
        $this->assertOpen($session);
        if ($amount <= 0) throw ValidationException::withMessages(['amount' => 'El monto debe ser mayor a cero.']);
        $movement = $this->createMovement($session, 'withdrawal', $amount, $this->cashMethodId(), $description, null, null, Auth::id() ?? $session->opened_by);
        AuditLogger::record('withdrawal_created', 'cash_movements', "Retiro de caja #{$session->id}");
        return $movement;
    }

    public function calculateExpectedCash(CashRegisterSession $session): float
    {
        $cashMethodId = $this->cashMethodId();
        $cashPositive = $session->movements()
            ->where('payment_method_id', $cashMethodId)
            ->whereIn('movement_type', ['opening', 'sale', 'income', 'adjustment'])
            ->sum('amount');
        $cashNegative = $session->movements()
            ->where('payment_method_id', $cashMethodId)
            ->whereIn('movement_type', ['expense', 'withdrawal', 'refund', 'cancellation'])
            ->sum('amount');

        return round((float) $cashPositive - (float) $cashNegative, 2);
    }

    public function calculateTotalsByPaymentMethod(CashRegisterSession $session): array
    {
        return $session->movements()
            ->with('paymentMethod')
            ->get()
            ->groupBy(fn (CashMovement $movement) => $movement->paymentMethod?->name ?? 'Sin medio')
            ->map(fn ($rows) => [
                'sales' => (float) $rows->where('movement_type', 'sale')->sum('amount'),
                'income' => (float) $rows->where('movement_type', 'income')->sum('amount'),
                'expense' => (float) $rows->where('movement_type', 'expense')->sum('amount'),
                'withdrawal' => (float) $rows->where('movement_type', 'withdrawal')->sum('amount'),
                'refund' => (float) $rows->where('movement_type', 'refund')->sum('amount'),
                'cancellation' => (float) $rows->where('movement_type', 'cancellation')->sum('amount'),
            ])
            ->toArray();
    }

    public function closeSession(CashRegisterSession $session, float $countedCash, int $userId, ?string $notes = null): CashRegisterSession
    {
        return DB::transaction(function () use ($session, $countedCash, $userId, $notes) {
            $session = CashRegisterSession::query()->whereKey($session->id)->lockForUpdate()->firstOrFail();
            $this->assertOpen($session);
            if ($countedCash < 0) throw ValidationException::withMessages(['counted_cash' => 'El efectivo contado no puede ser negativo.']);
            $expected = $this->calculateExpectedCash($session);
            $difference = round($countedCash - $expected, 2);
            $session->update([
                'status' => 'closed',
                'closed_by' => $userId,
                'expected_cash_amount' => $expected,
                'counted_cash_amount' => $countedCash,
                'cash_difference' => $difference,
                'closed_at' => now(),
                'notes' => trim(($session->notes ? $session->notes."\n" : '').($notes ?? '')) ?: $session->notes,
            ]);
            AuditLogger::record('closed', 'cash_register_sessions', "Caja cerrada #{$session->id}", userId: $userId);
            if ($difference != 0.0) {
                AuditLogger::record('difference_detected', 'cash_register_sessions', "Diferencia de caja #{$session->id}: {$difference}", userId: $userId);
            }

            return $session->load(['terminal', 'opener', 'closer', 'movements.paymentMethod']);
        });
    }

    public function cancelPosSale(Order $order, string $reason, bool $restoreStock = true, ?string $notes = null): PosSaleCancellation
    {
        return DB::transaction(function () use ($order, $reason, $restoreStock, $notes) {
            $order = Order::query()->whereKey($order->id)->lockForUpdate()->with(['payments', 'fulfillment.items'])->firstOrFail();
            if ($order->order_channel !== 'pos') throw ValidationException::withMessages(['order' => 'Solo se pueden anular ventas POS.']);
            if ($order->posCancellation()->exists() || $order->order_status === 'cancelled') throw ValidationException::withMessages(['order' => 'La venta ya fue anulada.']);
            $session = $order->cashRegisterSession ?: $this->requireOpenSession((int) $order->pos_terminal_id);

            $cancellation = PosSaleCancellation::query()->create([
                'order_id' => $order->id,
                'cash_register_session_id' => $session->id,
                'reason' => $reason,
                'notes' => $notes,
                'cancelled_by' => Auth::id() ?? (int) $order->sold_by,
                'cancelled_at' => now(),
                'restore_stock' => $restoreStock,
            ]);

            foreach ($order->payments as $payment) {
                $methodId = PosPaymentMethod::query()->where('code', $payment->payment_method)->value('id');
                $this->createMovement($session, 'cancellation', (float) $payment->amount, $methodId, "Anulación {$order->order_number}: {$reason}", PosSaleCancellation::class, $cancellation->id, Auth::id() ?? (int) $order->sold_by);
            }
            if ($restoreStock) $this->restoreOrderStock($order, 'Stock restaurado por anulación POS');
            $order->update(['order_status' => 'cancelled', 'payment_status' => 'refunded', 'fulfillment_status' => 'cancelled', 'cancelled_at' => now()]);
            $order->histories()->create(['status_type' => 'order', 'old_status' => 'confirmed', 'new_status' => 'cancelled', 'notes' => $reason, 'user_id' => Auth::id()]);
            AuditLogger::record('cancelled', 'pos_cancellations', "Venta POS anulada {$order->order_number}");

            return $cancellation;
        });
    }

    public function refundPosSale(Order $order, float $amount, string $reason, bool $restoreStock = false, string $refundMethod = 'cash', ?string $notes = null): PosRefund
    {
        return DB::transaction(function () use ($order, $amount, $reason, $restoreStock, $refundMethod, $notes) {
            $order = Order::query()->whereKey($order->id)->lockForUpdate()->with(['payments', 'posRefunds', 'fulfillment.items'])->firstOrFail();
            if ($order->order_channel !== 'pos') throw ValidationException::withMessages(['order' => 'Solo se pueden devolver ventas POS.']);
            if ($amount <= 0) throw ValidationException::withMessages(['refund_amount' => 'El monto debe ser mayor a cero.']);
            $refunded = (float) $order->posRefunds()->sum('refund_amount');
            if ($refunded + $amount > (float) $order->payments()->sum('amount')) throw ValidationException::withMessages(['refund_amount' => 'La devolución supera el total pagado.']);
            $session = $order->cashRegisterSession ?: $this->requireOpenSession((int) $order->pos_terminal_id);
            $methodId = PosPaymentMethod::query()->where('code', $refundMethod)->value('id') ?? $this->cashMethodId();

            $refund = PosRefund::query()->create([
                'order_id' => $order->id,
                'cash_register_session_id' => $session->id,
                'refund_amount' => $amount,
                'reason' => $reason,
                'refund_method' => $refundMethod,
                'processed_by' => Auth::id() ?? (int) $order->sold_by,
                'processed_at' => now(),
                'restore_stock' => $restoreStock,
                'notes' => $notes,
            ]);
            $this->createMovement($session, 'refund', $amount, $methodId, "Devolución {$order->order_number}: {$reason}", PosRefund::class, $refund->id, Auth::id() ?? (int) $order->sold_by);
            if ($restoreStock) $this->restoreOrderStock($order, 'Stock restaurado por devolución POS');
            $order->update(['payment_status' => ($refunded + $amount) >= (float) $order->grand_total ? 'refunded' : 'partially_paid']);
            AuditLogger::record('refunded', 'pos_refunds', "Devolución POS {$order->order_number}");

            return $refund;
        });
    }

    public function summary(CashRegisterSession $session): array
    {
        $session->loadMissing(['movements.paymentMethod', 'orders']);
        return [
            'opening' => (float) $session->opening_amount,
            'cashExpected' => $this->calculateExpectedCash($session),
            'sales' => (float) $session->movements->where('movement_type', 'sale')->sum('amount'),
            'income' => (float) $session->movements->where('movement_type', 'income')->sum('amount'),
            'expense' => (float) $session->movements->where('movement_type', 'expense')->sum('amount'),
            'withdrawal' => (float) $session->movements->where('movement_type', 'withdrawal')->sum('amount'),
            'refund' => (float) $session->movements->where('movement_type', 'refund')->sum('amount'),
            'cancellation' => (float) $session->movements->where('movement_type', 'cancellation')->sum('amount'),
            'byPaymentMethod' => $this->calculateTotalsByPaymentMethod($session),
        ];
    }

    private function createMovement(CashRegisterSession $session, string $type, float $amount, ?int $paymentMethodId, string $description, ?string $referenceType, ?int $referenceId, int $userId): CashMovement
    {
        if ($amount < 0) throw ValidationException::withMessages(['amount' => 'No se permiten montos negativos.']);
        return CashMovement::query()->create([
            'cash_register_session_id' => $session->id,
            'movement_type' => $type,
            'amount' => $amount,
            'payment_method_id' => $paymentMethodId,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'user_id' => $userId,
            'created_at' => now(),
        ]);
    }

    private function assertOpen(CashRegisterSession $session): void
    {
        if ($session->status !== 'open') throw ValidationException::withMessages(['cash_register' => 'La caja no está abierta.']);
    }

    private function cashMethodId(): ?int
    {
        return PosPaymentMethod::query()->where('code', 'cash')->value('id');
    }

    private function restoreOrderStock(Order $order, string $notes): void
    {
        $warehouseId = $order->posTerminal?->warehouse_id;
        if (! $warehouseId) return;
        foreach ($order->fulfillment?->items ?? [] as $item) {
            $this->inventoryService->increaseStock($warehouseId, $item->product_id, $item->product_variant_id, null, (float) $item->required_quantity, 'return_in', $notes, Order::class, $order->id);
        }
    }
}
