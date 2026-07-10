<?php

namespace App\Services;

use App\Models\CashMovement;
use App\Models\CashRegisterSession;
use App\Models\Coupon;
use App\Models\CustomerProfile;
use App\Models\ExternalOrderMapping;
use App\Models\ExternalProductMapping;
use App\Models\Integration;
use App\Models\IntegrationLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderShipment;
use App\Models\Promotion;
use App\Models\StockLevel;
use App\Models\StockMovement;
use App\Models\SyncJob;
use App\Models\WebhookEvent;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getExecutiveDashboard(array $filters = []): array
    {
        $today = now()->startOfDay();
        $month = now()->startOfMonth();

        $salesToday = (clone $this->ordersBaseQuery($filters))->where('created_at', '>=', $today)->sum('grand_total');
        $salesMonth = (clone $this->ordersBaseQuery($filters))->where('created_at', '>=', $month)->sum('grand_total');
        $ordersToday = (clone $this->ordersBaseQuery($filters))->where('created_at', '>=', $today)->count();
        $ordersCount = (clone $this->ordersBaseQuery($filters))->count();
        $salesTotal = (clone $this->ordersBaseQuery($filters))->sum('grand_total');

        $dailySales = (clone $this->ordersBaseQuery($filters))
            ->selectRaw('DATE(created_at) as label, SUM(grand_total) as total')
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->groupBy('label')
            ->orderBy('label')
            ->pluck('total', 'label');

        $channelSales = (clone $this->ordersBaseQuery($filters))
            ->selectRaw("COALESCE(order_channel, 'ecommerce') as label, SUM(grand_total) as total")
            ->groupBy('label')
            ->pluck('total', 'label');

        $orderStatuses = (clone $this->ordersBaseQuery($filters))
            ->selectRaw('order_status as label, COUNT(*) as total')
            ->groupBy('label')
            ->pluck('total', 'label');

        $topProducts = OrderItem::query()
            ->selectRaw('product_name as label, SUM(quantity) as total')
            ->whereHas('order', fn (Builder $query) => $this->applyDateFilters($query, $filters))
            ->groupBy('label')
            ->orderByDesc('total')
            ->limit(8)
            ->pluck('total', 'label');

        return [
            'cards' => [
                ['label' => 'Ventas del dia', 'value' => $this->money($salesToday)],
                ['label' => 'Ventas del mes', 'value' => $this->money($salesMonth)],
                ['label' => 'Pedidos del dia', 'value' => number_format($ordersToday, 0, ',', '.')],
                ['label' => 'Pedidos pendientes', 'value' => Order::query()->where('order_status', 'pending')->count()],
                ['label' => 'Ticket promedio', 'value' => $this->money($ordersCount > 0 ? $salesTotal / $ordersCount : 0)],
                ['label' => 'Productos vendidos', 'value' => number_format(OrderItem::query()->sum('quantity'), 0, ',', '.')],
                ['label' => 'Clientes nuevos', 'value' => CustomerProfile::query()->where('created_at', '>=', $month)->count()],
                ['label' => 'Stock critico', 'value' => StockLevel::query()->whereRaw('(physical_stock - reserved_stock) <= minimum_stock')->count()],
                ['label' => 'Ventas POS', 'value' => $this->money(Order::query()->where('order_channel', 'pos')->sum('grand_total'))],
                ['label' => 'Ventas ecommerce', 'value' => $this->money(Order::query()->where('order_channel', 'ecommerce')->sum('grand_total'))],
                ['label' => 'Devoluciones', 'value' => Order::query()->where('order_status', 'refunded')->count()],
                ['label' => 'Anulaciones', 'value' => Order::query()->where('order_status', 'cancelled')->count()],
            ],
            'charts' => [
                'Ventas por dia' => $this->chart($dailySales),
                'Ventas por canal' => $this->chart($channelSales),
                'Pedidos por estado' => $this->chart($orderStatuses),
                'Top productos' => $this->chart($topProducts),
            ],
        ];
    }

    public function getSalesReport(array $filters = []): array
    {
        $query = $this->ordersBaseQuery($filters)
            ->with(['seller', 'customerProfile', 'payments'])
            ->when($filters['order_status'] ?? null, fn ($q, $value) => $q->where('order_status', $value))
            ->when($filters['payment_status'] ?? null, fn ($q, $value) => $q->where('payment_status', $value))
            ->when($filters['seller_id'] ?? null, fn ($q, $value) => $q->where('sold_by', $value))
            ->when($filters['customer_id'] ?? null, fn ($q, $value) => $q->where('customer_profile_id', $value))
            ->when($filters['payment_method'] ?? null, fn ($q, $value) => $q->whereHas('payments', fn ($p) => $p->where('payment_method', $value)))
            ->latest();

        return $this->report(
            $query->paginate(20)->withQueryString(),
            [
                'Total vendido' => $this->money((clone $query)->sum('grand_total')),
                'Total descuentos' => $this->money((clone $query)->sum(DB::raw('item_discount_total + coupon_discount_total'))),
                'Total impuestos' => $this->money((clone $query)->sum('tax_total')),
                'Total envios' => $this->money((clone $query)->sum('shipping_total')),
                'Ticket promedio' => $this->money((clone $query)->avg('grand_total') ?? 0),
                'Cantidad pedidos' => (clone $query)->count(),
            ],
            ['Pedido', 'Fecha', 'Cliente', 'Canal', 'Vendedor', 'Subtotal', 'Descuentos', 'Envio', 'Impuestos', 'Total', 'Estado', 'Pago'],
            fn (Order $order) => [
                $order->order_number,
                $order->created_at?->format('d/m/Y H:i'),
                $order->customer_name,
                ucfirst($order->order_channel ?? 'ecommerce'),
                $order->seller?->name ?? '-',
                $this->money($order->subtotal),
                $this->money((float) $order->item_discount_total + (float) $order->coupon_discount_total),
                $this->money($order->shipping_total),
                $this->money($order->tax_total),
                $this->money($order->grand_total),
                $order->order_status,
                $order->payment_status,
            ]
        );
    }

    public function getProductsReport(array $filters = []): array
    {
        $query = OrderItem::query()
            ->with(['product.category', 'product.brand', 'variant'])
            ->select('order_items.*')
            ->whereHas('order', fn (Builder $order) => $this->applyDateFilters($this->applyChannelFilters($order, $filters), $filters))
            ->when($filters['product_id'] ?? null, fn ($q, $value) => $q->where('product_id', $value))
            ->when($filters['variant_id'] ?? null, fn ($q, $value) => $q->where('product_variant_id', $value))
            ->when($filters['category_id'] ?? null, fn ($q, $value) => $q->whereHas('product', fn ($p) => $p->where('category_id', $value)))
            ->when($filters['brand_id'] ?? null, fn ($q, $value) => $q->whereHas('product', fn ($p) => $p->where('brand_id', $value)));

        $items = $query->get()->groupBy(fn ($item) => ($item->product_id ?: 'pack-'.$item->product_pack_id).'-'.($item->product_variant_id ?: 0));
        $rows = $items->map(function (Collection $group) {
            $first = $group->first();
            $quantity = (float) $group->sum('quantity');
            $total = (float) $group->sum('line_total');
            $discount = (float) $group->sum('line_discount');
            $stock = StockLevel::query()
                ->where('product_id', $first->product_id)
                ->when($first->product_variant_id, fn ($q) => $q->where('product_variant_id', $first->product_variant_id))
                ->get()
                ->sum(fn ($stock) => $stock->available_stock);

            return [
                'product' => $first->product_name,
                'variant' => $first->variant_name ?: '-',
                'sku' => $first->sku ?: '-',
                'category' => $first->product?->category?->name ?? '-',
                'brand' => $first->product?->brand?->name ?? '-',
                'quantity' => $quantity,
                'total' => $total,
                'average_price' => $quantity > 0 ? $total / $quantity : 0,
                'average_discount' => $quantity > 0 ? $discount / $quantity : 0,
                'stock' => $stock,
                'margin' => $first->product?->cost_price ? $total - ($quantity * (float) $first->product->cost_price) : null,
            ];
        })->sortByDesc('total')->values();

        return [
            'summary' => [
                'Producto mas vendido' => $rows->sortByDesc('quantity')->first()['product'] ?? '-',
                'Mayor ingreso' => $rows->first()['product'] ?? '-',
                'Mayor descuento' => $rows->sortByDesc('average_discount')->first()['product'] ?? '-',
                'Menor rotacion' => $rows->sortBy('quantity')->first()['product'] ?? '-',
            ],
            'columns' => ['Producto', 'Variante', 'SKU', 'Categoria', 'Marca', 'Cantidad', 'Total vendido', 'Precio prom.', 'Desc. prom.', 'Stock actual', 'Margen est.'],
            'rows' => $rows->map(fn ($row) => [
                $row['product'], $row['variant'], $row['sku'], $row['category'], $row['brand'],
                number_format($row['quantity'], 0, ',', '.'), $this->money($row['total']), $this->money($row['average_price']),
                $this->money($row['average_discount']), number_format((int) $row['stock'], 0, ',', '.'),
                $row['margin'] === null ? '-' : $this->money($row['margin']),
            ]),
            'paginator' => null,
        ];
    }

    public function getInventoryReport(array $filters = []): array
    {
        $query = StockLevel::query()
            ->with(['product.category', 'product.brand', 'variant', 'warehouse', 'location'])
            ->when($filters['warehouse_id'] ?? null, fn ($q, $value) => $q->where('warehouse_id', $value))
            ->when($filters['product_id'] ?? null, fn ($q, $value) => $q->where('product_id', $value))
            ->when($filters['category_id'] ?? null, fn ($q, $value) => $q->whereHas('product', fn ($p) => $p->where('category_id', $value)))
            ->when($filters['brand_id'] ?? null, fn ($q, $value) => $q->whereHas('product', fn ($p) => $p->where('brand_id', $value)))
            ->when(($filters['low_stock'] ?? null) === '1', fn ($q) => $q->whereRaw('(physical_stock - reserved_stock) <= minimum_stock'));

        $rows = $query->paginate(20)->withQueryString();

        return $this->report(
            $rows,
            [
                'Productos sin stock' => StockLevel::query()->whereRaw('(physical_stock - reserved_stock) <= 0')->count(),
                'Productos bajo minimo' => StockLevel::query()->whereRaw('(physical_stock - reserved_stock) <= minimum_stock')->count(),
                'Reservas activas' => DB::table('stock_reservations')->where('status', 'active')->count(),
                'Stock fisico total' => number_format((int) StockLevel::query()->sum('physical_stock'), 0, ',', '.'),
            ],
            ['Producto', 'Variante', 'SKU', 'Bodega', 'Ubicacion', 'Fisico', 'Reservado', 'Disponible', 'Minimo', 'Estado'],
            fn (StockLevel $stock) => [
                $stock->product?->name ?? '-',
                $stock->variant?->name ?? '-',
                $stock->variant?->sku ?? $stock->product?->sku ?? '-',
                $stock->warehouse?->name ?? '-',
                $stock->location?->name ?? '-',
                number_format((int) $stock->physical_stock, 0, ',', '.'),
                number_format((int) $stock->reserved_stock, 0, ',', '.'),
                number_format((int) $stock->available_stock, 0, ',', '.'),
                number_format((int) $stock->minimum_stock, 0, ',', '.'),
                $this->stockStatus($stock),
            ]
        );
    }

    public function getKardexReport(array $filters = []): array
    {
        $query = StockMovement::query()
            ->with(['product', 'variant', 'warehouse', 'user'])
            ->when($filters['product_id'] ?? null, fn ($q, $value) => $q->where('product_id', $value))
            ->when($filters['variant_id'] ?? null, fn ($q, $value) => $q->where('product_variant_id', $value))
            ->when($filters['warehouse_id'] ?? null, fn ($q, $value) => $q->where('warehouse_id', $value))
            ->when($filters['movement_type'] ?? null, fn ($q, $value) => $q->where('movement_type', $value));

        $this->applyDateFilters($query, $filters);

        return $this->report(
            $query->latest()->paginate(20)->withQueryString(),
            ['Movimientos' => (clone $query)->count(), 'Cantidad total' => number_format((int) (clone $query)->sum('quantity'), 0, ',', '.')],
            ['Fecha', 'Producto', 'Variante', 'Bodega', 'Movimiento', 'Cantidad', 'Anterior', 'Nuevo', 'Referencia', 'Usuario', 'Observacion'],
            fn (StockMovement $movement) => [
                $movement->created_at?->format('d/m/Y H:i'),
                $movement->product?->name ?? '-',
                $movement->variant?->name ?? '-',
                $movement->warehouse?->name ?? '-',
                $movement->movement_type,
                number_format((int) $movement->quantity, 0, ',', '.'),
                number_format((int) $movement->previous_stock, 0, ',', '.'),
                number_format((int) $movement->new_stock, 0, ',', '.'),
                trim(($movement->reference_type ?? '').' #'.($movement->reference_id ?? ''), ' #') ?: '-',
                $movement->user?->name ?? '-',
                $movement->notes ?? '-',
            ]
        );
    }

    public function getCustomersReport(array $filters = []): array
    {
        $query = CustomerProfile::query()
            ->with('addresses')
            ->when($filters['customer_type'] ?? null, fn ($q, $value) => $q->where('customer_type', $value))
            ->when($filters['status'] ?? null, fn ($q, $value) => $q->where('is_active', $value === 'active'))
            ->when(isset($filters['newsletter']), fn ($q) => $q->where('newsletter', (bool) $filters['newsletter']))
            ->when($filters['customer_id'] ?? null, fn ($q, $value) => $q->whereKey($value));

        $this->applyDateFilters($query, $filters);

        $profiles = $query->paginate(20)->withQueryString();
        $ordersByCustomer = Order::query()
            ->selectRaw('customer_profile_id, COUNT(*) as orders_count, SUM(grand_total) as total, AVG(grand_total) as average_ticket, MAX(created_at) as last_order_at')
            ->whereNotNull('customer_profile_id')
            ->groupBy('customer_profile_id')
            ->get()
            ->keyBy('customer_profile_id');

        return $this->report(
            $profiles,
            [
                'Clientes nuevos' => CustomerProfile::query()->where('created_at', '>=', now()->startOfMonth())->count(),
                'Clientes recurrentes' => $ordersByCustomer->where('orders_count', '>', 1)->count(),
                'Clientes sin compra' => CustomerProfile::query()->whereNotIn('id', $ordersByCustomer->keys())->count(),
                'Clientes empresa' => CustomerProfile::query()->where('customer_type', 'company')->count(),
                'Newsletter' => CustomerProfile::query()->where('newsletter', true)->count(),
            ],
            ['Cliente', 'Email', 'Telefono', 'Tipo', 'Pedidos', 'Total comprado', 'Ticket prom.', 'Ultima compra', 'Puntos', 'Estado'],
            function (CustomerProfile $profile) use ($ordersByCustomer) {
                $stats = $ordersByCustomer->get($profile->id);

                return [
                    $profile->display_name,
                    $profile->email,
                    $profile->phone,
                    $profile->customer_type,
                    $stats->orders_count ?? 0,
                    $this->money($stats->total ?? 0),
                    $this->money($stats->average_ticket ?? 0),
                    $stats?->last_order_at ? Carbon::parse($stats->last_order_at)->format('d/m/Y') : '-',
                    number_format((float) $profile->reward_points, 0, ',', '.'),
                    $profile->is_active ? 'Activo' : 'Inactivo',
                ];
            }
        );
    }

    public function getPosReport(array $filters = []): array
    {
        $filters['channel'] = 'pos';
        $query = $this->ordersBaseQuery($filters)
            ->with(['posTerminal', 'seller', 'payments'])
            ->when($filters['terminal_id'] ?? null, fn ($q, $value) => $q->where('pos_terminal_id', $value))
            ->when($filters['seller_id'] ?? null, fn ($q, $value) => $q->where('sold_by', $value))
            ->when($filters['payment_method'] ?? null, fn ($q, $value) => $q->whereHas('payments', fn ($p) => $p->where('payment_method', $value)));

        return $this->report(
            $query->latest()->paginate(20)->withQueryString(),
            [
                'Ventas POS del dia' => $this->money(Order::query()->where('order_channel', 'pos')->whereDate('created_at', today())->sum('grand_total')),
                'Ticket promedio POS' => $this->money((clone $query)->avg('grand_total') ?? 0),
                'Cantidad ventas' => (clone $query)->count(),
            ],
            ['Fecha', 'Terminal', 'Vendedor', 'Venta/Pedido', 'Cliente', 'Medio de pago', 'Total', 'Estado', 'Comprobante'],
            fn (Order $order) => [
                $order->created_at?->format('d/m/Y H:i'),
                $order->posTerminal?->name ?? '-',
                $order->seller?->name ?? '-',
                $order->order_number,
                $order->customer_name,
                $order->payments->pluck('payment_label')->filter()->implode(', ') ?: '-',
                $this->money($order->grand_total),
                $order->order_status,
                route('pos.orders.receipt', $order),
            ]
        );
    }

    public function getCashReport(array $filters = []): array
    {
        $query = CashRegisterSession::query()
            ->with(['terminal', 'opener', 'closer', 'movements'])
            ->when($filters['terminal_id'] ?? null, fn ($q, $value) => $q->where('pos_terminal_id', $value))
            ->when($filters['seller_id'] ?? null, fn ($q, $value) => $q->where('opened_by', $value))
            ->when($filters['status'] ?? null, fn ($q, $value) => $q->where('status', $value));

        $this->applyDateFilters($query, $filters, 'opened_at');

        return $this->report(
            $query->latest('opened_at')->paginate(20)->withQueryString(),
            [
                'Cajas abiertas' => CashRegisterSession::query()->where('status', 'open')->count(),
                'Cajas cerradas' => CashRegisterSession::query()->where('status', 'closed')->count(),
                'Diferencias positivas' => CashRegisterSession::query()->where('cash_difference', '>', 0)->count(),
                'Diferencias negativas' => CashRegisterSession::query()->where('cash_difference', '<', 0)->count(),
                'Efectivo esperado' => $this->money(CashRegisterSession::query()->sum('expected_cash_amount')),
            ],
            ['Terminal', 'Apertura', 'Fecha apertura', 'Fecha cierre', 'Inicial', 'Ventas efectivo', 'Ventas tarjeta', 'Transferencias', 'Ingresos', 'Egresos', 'Retiros', 'Devoluciones', 'Anulaciones', 'Esperado', 'Contado', 'Diferencia', 'Estado'],
            fn (CashRegisterSession $session) => $this->cashRow($session)
        );
    }

    public function getShippingReport(array $filters = []): array
    {
        $query = OrderShipment::query()
            ->with(['order', 'carrier', 'service'])
            ->when($filters['carrier_id'] ?? null, fn ($q, $value) => $q->where('shipping_carrier_id', $value))
            ->when($filters['service_id'] ?? null, fn ($q, $value) => $q->where('shipping_service_id', $value))
            ->when($filters['status'] ?? null, fn ($q, $value) => $q->where('shipping_status', $value));

        $this->applyDateFilters($query, $filters);

        return $this->report(
            $query->latest()->paginate(20)->withQueryString(),
            [
                'Pendientes' => OrderShipment::query()->where('shipping_status', 'pending')->count(),
                'Despachados' => OrderShipment::query()->where('shipping_status', 'shipped')->count(),
                'Entregados' => OrderShipment::query()->where('shipping_status', 'delivered')->count(),
                'Devueltos' => OrderShipment::query()->where('shipping_status', 'returned')->count(),
                'Costo total' => $this->money(OrderShipment::query()->sum('shipping_cost')),
            ],
            ['Pedido', 'Cliente', 'Transportista', 'Servicio', 'Costo', 'Estado', 'Tracking', 'Despacho', 'Entrega'],
            fn (OrderShipment $shipment) => [
                $shipment->order?->order_number ?? '-',
                $shipment->order?->customer_name ?? '-',
                $shipment->carrier?->name ?? $shipment->carrier_name ?? '-',
                $shipment->service?->name ?? $shipment->service_name ?? '-',
                $this->money($shipment->shipping_cost),
                $shipment->shipping_status,
                $shipment->tracking_number ?: '-',
                $shipment->shipped_at?->format('d/m/Y') ?? '-',
                $shipment->delivered_at?->format('d/m/Y') ?? '-',
            ]
        );
    }

    public function getPromotionsReport(array $filters = []): array
    {
        $coupons = Coupon::query()->latest()->get()->map(fn (Coupon $coupon) => [
            'name' => $coupon->code,
            'type' => 'Cupon',
            'uses' => $coupon->usage_count,
            'discount' => 0,
            'sales' => '-',
            'ticket' => '-',
            'channel' => 'Todos',
            'status' => $coupon->is_active ? 'Activo' : 'Inactivo',
        ]);

        $promotions = Promotion::query()->latest()->get()->map(fn (Promotion $promotion) => [
            'name' => $promotion->name,
            'type' => $promotion->promotion_type,
            'uses' => $promotion->usage_count,
            'discount' => 0,
            'sales' => '-',
            'ticket' => '-',
            'channel' => $promotion->customer_role ?: 'Todos',
            'status' => $promotion->is_active ? 'Activo' : 'Inactivo',
        ]);

        $rows = $coupons->concat($promotions);

        return [
            'summary' => [
                'Cupon mas usado' => $coupons->sortByDesc('uses')->first()['name'] ?? '-',
                'Total descuentos otorgados' => $this->money(Order::query()->sum('coupon_discount_total')),
                'Campanas activas' => Promotion::query()->where('is_active', true)->count(),
                'Campanas vencidas' => Promotion::query()->whereNotNull('ends_at')->where('ends_at', '<', now())->count(),
            ],
            'columns' => ['Promocion/cupon', 'Tipo', 'Usos', 'Descuento total', 'Ventas asociadas', 'Ticket promedio', 'Canal', 'Estado'],
            'rows' => $rows->map(fn ($row) => [$row['name'], $row['type'], $row['uses'], $this->money($row['discount']), $row['sales'], $row['ticket'], $row['channel'], $row['status']]),
            'paginator' => null,
        ];
    }

    public function getIntegrationsReport(array $filters = []): array
    {
        $query = IntegrationLog::query()
            ->with('integration')
            ->when($filters['integration_id'] ?? null, fn ($q, $value) => $q->where('integration_id', $value))
            ->when($filters['status'] ?? null, fn ($q, $value) => $q->where('status', $value))
            ->when($filters['event_type'] ?? null, fn ($q, $value) => $q->where('event_type', 'like', "%{$value}%"));

        $this->applyDateFilters($query, $filters);

        return $this->report(
            $query->latest('created_at')->paginate(20)->withQueryString(),
            [
                'Integraciones activas' => Integration::query()->where('is_active', true)->count(),
                'Logs fallidos' => IntegrationLog::query()->where('status', 'failed')->count(),
                'Jobs pendientes' => SyncJob::query()->where('status', 'pending')->count(),
                'Webhooks hoy' => WebhookEvent::query()->whereDate('created_at', today())->count(),
                'Productos mapeados' => ExternalProductMapping::query()->count(),
                'Pedidos externos importados' => ExternalOrderMapping::query()->where('import_status', 'imported')->count(),
            ],
            ['Integracion', 'Evento', 'Direccion', 'Estado', 'Fecha', 'Referencia', 'Error', 'Intentos'],
            fn (IntegrationLog $log) => [
                $log->integration?->name ?? '-',
                $log->event_type,
                $log->direction,
                $log->status,
                $log->created_at?->format('d/m/Y H:i'),
                $log->external_reference ?: ($log->reference_type ? $log->reference_type.' #'.$log->reference_id : '-'),
                $log->error_message ?: '-',
                $log->attempts,
            ]
        );
    }

    public function applyDateFilters($query, array $filters, string $column = 'created_at')
    {
        return $query
            ->when($filters['date_from'] ?? null, fn ($q, $value) => $q->whereDate($column, '>=', $value))
            ->when($filters['date_to'] ?? null, fn ($q, $value) => $q->whereDate($column, '<=', $value));
    }

    public function applyChannelFilters($query, array $filters)
    {
        return $query->when(($filters['channel'] ?? null) && $filters['channel'] !== 'all', fn ($q, $value) => $q->where('order_channel', $value));
    }

    public function calculateTotals($collection): array
    {
        return [
            'count' => $collection instanceof LengthAwarePaginator ? $collection->total() : collect($collection)->count(),
        ];
    }

    public function prepareChartData($data): array
    {
        return $this->chart(collect($data));
    }

    private function ordersBaseQuery(array $filters): Builder
    {
        $query = Order::query();
        $this->applyDateFilters($query, $filters);
        $this->applyChannelFilters($query, $filters);

        return $query;
    }

    private function report($paginator, array $summary, array $columns, callable $mapper): array
    {
        return [
            'summary' => $summary,
            'columns' => $columns,
            'rows' => collect($paginator->items())->map($mapper),
            'paginator' => $paginator,
        ];
    }

    private function chart(Collection $values): array
    {
        return ['labels' => $values->keys()->values(), 'values' => $values->values()->map(fn ($value) => round((float) $value, 2))];
    }

    private function money($value): string
    {
        return '$'.number_format((float) $value, 0, ',', '.');
    }

    private function stockStatus(StockLevel $stock): string
    {
        if ($stock->available_stock <= 0) {
            return 'Sin stock';
        }

        if ($stock->available_stock <= (float) $stock->minimum_stock) {
            return 'Bajo stock';
        }

        if ($stock->maximum_stock !== null && $stock->available_stock > (float) $stock->maximum_stock) {
            return 'Sobrestock';
        }

        return 'Disponible';
    }

    private function cashRow(CashRegisterSession $session): array
    {
        $sum = fn (string $type, ?string $payment = null) => $session->movements
            ->where('movement_type', $type)
            ->when($payment, fn ($items) => $items->filter(fn (CashMovement $movement) => $movement->paymentMethod?->payment_type === $payment))
            ->sum('amount');

        $cardSales = $session->movements
            ->where('movement_type', 'sale')
            ->filter(fn (CashMovement $movement) => in_array($movement->paymentMethod?->payment_type, ['debit_card', 'credit_card'], true))
            ->sum('amount');

        return [
            $session->terminal?->name ?? '-',
            $session->opener?->name ?? '-',
            $session->opened_at?->format('d/m/Y H:i'),
            $session->closed_at?->format('d/m/Y H:i') ?? '-',
            $this->money($session->opening_amount),
            $this->money($sum('sale', 'cash')),
            $this->money($cardSales),
            $this->money($sum('sale', 'bank_transfer')),
            $this->money($sum('income')),
            $this->money($sum('expense')),
            $this->money($sum('withdrawal')),
            $this->money($sum('refund')),
            $this->money($sum('cancellation')),
            $this->money($session->expected_cash_amount),
            $session->counted_cash_amount === null ? '-' : $this->money($session->counted_cash_amount),
            $session->cash_difference === null ? '-' : $this->money($session->cash_difference),
            $session->status,
        ];
    }
}
