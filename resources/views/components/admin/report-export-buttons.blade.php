@props(['reportType', 'filters' => []])

@php
    $base = array_filter($filters, fn ($value) => $value !== null && $value !== '');
@endphp

<div class="btn-group">
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.reports.export', array_merge($base, ['report' => $reportType, 'format' => 'csv'])) }}">CSV</a>
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.reports.export', array_merge($base, ['report' => $reportType, 'format' => 'excel'])) }}">Excel</a>
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.reports.export', array_merge($base, ['report' => $reportType, 'format' => 'pdf'])) }}" target="_blank">PDF</a>
</div>
