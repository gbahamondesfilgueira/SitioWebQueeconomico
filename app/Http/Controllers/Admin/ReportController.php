<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditLogger;
use App\Services\ReportExportService;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private readonly ReportService $reports,
        private readonly ReportExportService $exports
    ) {
    }

    public function dashboard(Request $request)
    {
        AuditLogger::record('viewed', 'dashboard', 'Dashboard ejecutivo consultado');

        return view('admin.reports.dashboard', [
            'dashboard' => $this->reports->getExecutiveDashboard($this->filters($request)),
            'filters' => $this->filters($request),
        ]);
    }

    public function sales(Request $request)
    {
        return $this->showReport($request, 'sales', 'Reporte de ventas', 'admin.reports.sales', fn ($filters) => $this->reports->getSalesReport($filters));
    }

    public function products(Request $request)
    {
        return $this->showReport($request, 'products', 'Reporte de productos', 'admin.reports.products', fn ($filters) => $this->reports->getProductsReport($filters));
    }

    public function inventory(Request $request)
    {
        return $this->showReport($request, 'inventory', 'Reporte de inventario', 'admin.reports.inventory', fn ($filters) => $this->reports->getInventoryReport($filters));
    }

    public function kardex(Request $request)
    {
        return $this->showReport($request, 'kardex', 'Reporte de Kardex', 'admin.reports.kardex', fn ($filters) => $this->reports->getKardexReport($filters));
    }

    public function customers(Request $request)
    {
        return $this->showReport($request, 'customers', 'Reporte de clientes', 'admin.reports.customers', fn ($filters) => $this->reports->getCustomersReport($filters));
    }

    public function pos(Request $request)
    {
        return $this->showReport($request, 'pos', 'Reporte POS', 'admin.reports.pos', fn ($filters) => $this->reports->getPosReport($filters));
    }

    public function cash(Request $request)
    {
        return $this->showReport($request, 'cash', 'Reporte de caja', 'admin.reports.cash', fn ($filters) => $this->reports->getCashReport($filters));
    }

    public function shipping(Request $request)
    {
        return $this->showReport($request, 'shipping', 'Reporte de envios', 'admin.reports.shipping', fn ($filters) => $this->reports->getShippingReport($filters));
    }

    public function promotions(Request $request)
    {
        return $this->showReport($request, 'promotions', 'Reporte de promociones y cupones', 'admin.reports.promotions', fn ($filters) => $this->reports->getPromotionsReport($filters));
    }

    public function integrations(Request $request)
    {
        return $this->showReport($request, 'integrations', 'Reporte de integraciones', 'admin.reports.integrations', fn ($filters) => $this->reports->getIntegrationsReport($filters));
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'report' => ['required', 'string', 'in:sales,products,inventory,kardex,customers,pos,cash,shipping,promotions,integrations'],
            'format' => ['required', 'string', 'in:csv,excel,pdf'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $filters = $this->filters($request);

        return match ($validated['format']) {
            'excel' => $this->exports->exportExcel($validated['report'], $filters),
            'pdf' => $this->exports->exportPdf($validated['report'], $filters),
            default => $this->exports->exportCsv($validated['report'], $filters),
        };
    }

    private function showReport(Request $request, string $module, string $title, string $view, callable $resolver)
    {
        $filters = $this->filters($request);
        AuditLogger::record('viewed', 'reports', "{$title} consultado");

        return view($view, [
            'title' => $title,
            'reportType' => $module,
            'report' => $resolver($filters),
            'filters' => $filters,
        ]);
    }

    private function filters(Request $request): array
    {
        return $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'channel' => ['nullable', 'string', 'max:30'],
            'status' => ['nullable', 'string', 'max:50'],
            'order_status' => ['nullable', 'string', 'max:50'],
            'payment_status' => ['nullable', 'string', 'max:50'],
            'movement_type' => ['nullable', 'string', 'max:80'],
            'event_type' => ['nullable', 'string', 'max:120'],
            'payment_method' => ['nullable', 'string', 'max:80'],
            'low_stock' => ['nullable', 'in:0,1'],
            'customer_type' => ['nullable', 'string', 'max:30'],
            'newsletter' => ['nullable', 'in:0,1'],
            'seller_id' => ['nullable', 'integer'],
            'customer_id' => ['nullable', 'integer'],
            'product_id' => ['nullable', 'integer'],
            'variant_id' => ['nullable', 'integer'],
            'category_id' => ['nullable', 'integer'],
            'brand_id' => ['nullable', 'integer'],
            'warehouse_id' => ['nullable', 'integer'],
            'terminal_id' => ['nullable', 'integer'],
            'carrier_id' => ['nullable', 'integer'],
            'service_id' => ['nullable', 'integer'],
            'integration_id' => ['nullable', 'integer'],
        ]);
    }
}
