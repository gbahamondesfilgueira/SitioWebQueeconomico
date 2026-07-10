<?php

namespace App\Services;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportService
{
    public function __construct(private readonly ReportService $reports)
    {
    }

    public function exportCsv(string $reportType, array $filters = []): StreamedResponse
    {
        return $this->streamDelimited($reportType, $filters, 'csv', 'text/csv; charset=UTF-8', ',');
    }

    public function exportExcel(string $reportType, array $filters = []): StreamedResponse
    {
        return $this->streamDelimited($reportType, $filters, 'xls', 'application/vnd.ms-excel; charset=UTF-8', "\t");
    }

    public function exportPdf(string $reportType, array $filters = []): Response
    {
        $report = $this->resolveReport($reportType, $filters);
        AuditLogger::record('exported', 'report_exports', "Reporte {$reportType} exportado como PDF imprimible");

        $html = view('admin.reports.export-print', [
            'title' => $this->title($reportType),
            'report' => $report,
            'filters' => $filters,
        ])->render();

        return response($html, 200, ['Content-Type' => 'text/html; charset=UTF-8']);
    }

    private function streamDelimited(string $reportType, array $filters, string $extension, string $contentType, string $delimiter): StreamedResponse
    {
        $report = $this->resolveReport($reportType, $filters);
        AuditLogger::record('exported', 'report_exports', "Reporte {$reportType} exportado como {$extension}");

        return response()->streamDownload(function () use ($report, $delimiter) {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, $report['columns'] ?? [], $delimiter);

            foreach (($report['rows'] ?? []) as $row) {
                fputcsv($output, collect($row)->map(fn ($value) => is_scalar($value) ? (string) $value : json_encode($value))->all(), $delimiter);
            }

            fclose($output);
        }, $reportType.'-'.now()->format('Ymd-His').'.'.$extension, ['Content-Type' => $contentType]);
    }

    private function resolveReport(string $reportType, array $filters): array
    {
        return match ($reportType) {
            'sales' => $this->reports->getSalesReport($filters),
            'products' => $this->reports->getProductsReport($filters),
            'inventory' => $this->reports->getInventoryReport($filters),
            'kardex' => $this->reports->getKardexReport($filters),
            'customers' => $this->reports->getCustomersReport($filters),
            'pos' => $this->reports->getPosReport($filters),
            'cash' => $this->reports->getCashReport($filters),
            'shipping' => $this->reports->getShippingReport($filters),
            'promotions' => $this->reports->getPromotionsReport($filters),
            'integrations' => $this->reports->getIntegrationsReport($filters),
            default => $this->reports->getSalesReport($filters),
        };
    }

    private function title(string $reportType): string
    {
        return match ($reportType) {
            'sales' => 'Reporte de ventas',
            'products' => 'Reporte de productos',
            'inventory' => 'Reporte de inventario',
            'kardex' => 'Reporte de Kardex',
            'customers' => 'Reporte de clientes',
            'pos' => 'Reporte POS',
            'cash' => 'Reporte de caja',
            'shipping' => 'Reporte de envios',
            'promotions' => 'Reporte de promociones',
            'integrations' => 'Reporte de integraciones',
            default => 'Reporte',
        };
    }
}
